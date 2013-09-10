<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Json extends \dependencies\BaseComponent
{
  
  protected $permissions = array(
    'create_post' => 1,
    'create_topic' => 1
  );
  
  //Creates a new forum post in the given topic under the currently logged in user.
  public function create_post($data, $parameters)
  {
    
    //Check if the user is logged in.
    if(!tx('Account')->user->check('login')){
      throw new \exception\Authorisation("You can't create forum posts when not logged in.");
    }
    
    #TODO: Authorise user write permissions in the associated forum.
    
    //Sanitize the content.
    // $data->content->set(
    //   htmlspecialchars($data->content->get())
    // );
    
    //Reference interesting variables.
    $uid = tx('Account')->user->id;
    
    //Create the model.
    return $this->model('Posts')
    
    //Merge the save-data.
    ->merge($data->having('topic_id', 'parent_id', 'content'))
    
    //Set the user_id.
    ->push('user_id', $uid)
    
    //Save to the database.
    ->save();
    
  }
  
  //Update the given post by the currently logged in user.
  public function update_post($data, $parameters)
  {
    
    //Check if the user is logged in.
    if(!tx('Account')->user->check('login')){
      throw new \exception\Authorisation("You can't create forum posts when not logged in.");
    }
    
    #TODO: Authorise user edit permissions in the associated forum.
    
    //Reference interesting variables.
    $uid = tx('Account')->user->id;
    $pid = $parameters[0];
    
    //Validate them.
    $pid->validate('Post ID', array('required', 'number'=>'int', 'gt'=>0));
    
    //Get the post.
    $post = $this->table('Posts')->pk($pid)->execute_single();
    
    //Check if the user is allowed to update this post.
    if($uid->get('int') !== $post->user_id->get('int')){
      throw new \exception\Authorisation("You can't edit other peoples posts.");
    }
    
    //Merge the new contents.
    $post->merge($data->having('content'))
    
    //Save to the database.
    ->save();
    
    #TODO: Create a new record in a post_updates table.
    
    //Return the new Post.
    return $post;
    
  }
  
  //Delete the given post.
  public function get_delete_post($data, $params)
  {
    
    //Check if the user is a moderator.
    if(!tx('Account')->user->check('login') || tx('Account')->user->level->get() < 2){
      throw new \exception\Authorisation("You can't delete a forum post when not logged in as an moderator.");
    }
    
    #TODO: Authorise user delete permissions in the associated forum.

    //Delete post.
    return $this
      ->table('Posts')->pk($params[0])
      ->execute_single()
      ->not('empty')->success(function($row){
        $row->delete();
      });
    
  }
  
  //Creates a new topic in the given forum under the currently logged in user.
  public function create_topic($data, $parameters)
  {
    
    //Check if the user is logged in.
    if(!tx('Account')->user->check('login')){
      throw new \exception\Authorisation("You can't create forum posts when not logged in.");
    }
    
    #TODO: Authorise user write permissions in the associated forum.
    
    //Sanitize the title and content.
    $data->merge(array(
      'title' => htmlspecialchars($data->title->get()),
      'content' => /*htmlspecialchars(*/$data->content->get()/*)*/
    ));
    
    //Reference interesting variables.
    $uid = tx('Account')->user->id;
    
    //Create and save the topic.
    $topic = $this->model('Topics')
    ->merge($data->having('forum_id', 'title'))
    ->push('user_id', $uid)
    ->save();
    
    //Create and save the topic starter.
    $post = $this->model('Posts')
    ->merge($data->having('content'))
    ->push('user_id', $uid)
    ->push('topic_id', $topic->id)
    ->save();
    
    //Set the topic starter in the topic.
    $topic->push('post_id', $post->id)
    ->save();
    
    //Return the saved data.
    return Data($post->get())->merge($topic->get());
    
  }
  
  public function create_topic_move($data, $params)
  {
    
    return mk('Sql')
      ->table('forum', 'Topics')
      ->pk($params->{0})
      ->execute_single()
      ->is('empty', function(){
        throw new \exception\NotFound('No topic with this ID.');
      })
      ->merge($data->having('forum_id'))
      ->save()
      ->is(true, function($topic)use($data){
        
        $topic->extra;
        
        $topic->link->data->merge(array('fid'=>$data->forum_id));
        $topic->link->segments->query->set(http_build_query($topic->link->data->as_array(), null, '&'));
        $topic->link->rebuild_output();
        
      });
    
  }

  //Delete the given topic.
  public function get_delete_topic($data, $params)
  {
    
    //Check if the user is a moderator.
    if(!tx('Account')->user->check('login') || tx('Account')->user->level->get() < 2){
      throw new \exception\Authorisation("You can't delete a forum topic when not logged in as an moderator.");
    }
    
    #TODO: Authorise user delete permissions in the associated forum.

    //Get the topic.
    $topic = $this
      ->table('Topics')->pk($params[0])
      ->execute_single();

    //Delete topic.
    $topic->not('empty')->success(function($row){
      $row->delete();
    });

    //#TODO Check if the linked post is the first & only one, so we can delete it.

  }
  
  /**
   * Gets forum information based on the page ID.
   * @return \components\forum\models\Forums
   */
  protected function get_page_forum($data, $params)
  {
    
    return mk('Sql')
      ->table('forum', 'Forums')
      ->join('PageLink', $PL)
      ->where("$PL.page_id", $params->{0})
      ->execute_single()
      
      ->is('empty', function()use($data, $params){
        
        if($data->allow_create->validate('Allow create', array('boolean'))->is_true()){
          
          $forum = mk('Sql')
            ->model('forum', 'Forums')
            ->merge(array(
              'title' => 'New forum'
            ))
            ->hsave();
          
          mk('Sql')
            ->model('forum', 'PageLink')
            ->merge(array(
              'forum_id' => $forum->id,
              'page_id' => $params->{0}
            ))
            ->save();
          
          return $forum;
          
        }
        
        else
          throw new \exception\NotFound('No forum associated with page ID "%s"', $params->{0});
        
      })
      
      //Include custom getters.
      ->is('set', function($forum)use($data){
        
        $subfora = $data->include_subfora->validate('Include subfora', array('boolean'))->is_true();
        $extras = $data->include_extras->validate('Include extras', array('boolean'))->is_true();
        $link = $data->include_link->validate('Include link', array('boolean'))->is_true();
        
        // $go = function(){};
        $go = function($forum)use($subfora, $extras, $link, &$go){
          
          if($extras) $forum->extra;
          if($link)   $forum->link;
          
          if($subfora){
            $forum->subfora->each($go);
          }
          
        };
        
        $go($forum);
        
      })
    ;
    
  }
  
  public function create_forum($data, $params)
  {
    
    return mk('Sql')
      ->model('forum', 'Forums')
      ->merge($data->having('title', 'description'))
      ->hsave($data->parent_forum_id, 0)
      ->is(true, function($forum){
        $forum->extra;
        $forum->link;
      });
    
  }
  
  public function update_forum($data, $params)
  {
    
    return mk('Sql')
      ->table('forum', 'Forums')
      ->pk($params->{0})
      ->execute_single()
      ->is('empty', function()use($data){
        throw new \exception\NotFound('The forum with ID %s was not found', $data->id);
      })
      ->merge($data->having('title', 'description'))
      ->save();
    
  }
  
  public function delete_forum($data, $params)
  {
    
    return mk('Sql')
      ->table('forum', 'Forums')
      ->pk($params->{0})
      ->execute_single()
      ->is('empty', function()use($data){
        throw new \exception\NotFound('The forum with ID %s was not found', $data->id);
      })
      ->hdelete();
    
  }
  
  public function update_page_forum($data, $params)
  {
    
    //Update forum information.
    $forum = mk('Sql')
      ->table('forum', 'Forums')
      ->pk($data->id)
      ->execute_single()
      ->is('empty', function()use($data){
        throw new \exception\NotFound('The forum with ID %s was not found', $data->id);
      })
      ->merge($data->having('title', 'description'))
      ->save();
    
    //Update page association.
    mk('Sql')
      ->table('forum', 'PageLink')
      ->where('page_id', $data->page_id)
      ->execute_single()
      ->is('empty', function(){
        return mk('Sql')
          ->model('forum', 'PageLink');
      })
      ->merge(array(
        'page_id' => $data->page_id,
        'forum_id' => $forum->id
      ))
      ->save();
    
    //Update subfora ordering and nesting.
    $order = $data->subfora->as_array();
    
    #TODO: Can be more efficient, (7 queries per subforum a.t.m.)
    #TODO: USE SQL TRANSACTION HERE!
    
    //Loop in reversed order.
    //By prepending the items, this will create the right order.
    for($i = count($order)-1; $i >= 0; $i--)
    {
      
      $item = $order[$i];
      
      $target = mk('Sql')
        ->table('forum', 'Forums')
        ->pk($item['item_id'])
        ->execute_single()
        ->is('empty', function()use($item){
          throw new \exception\NotFound('The subforum with ID %s was not found', $item['item_id']);
        })
        ->hsave($item['parent_id'] == 'root' ? $forum->id : $item['parent_id']);
      
    }
    
    return $forum->is(true, function($forum){
        
        //Loops through all subfora to include subfora, extras and links.
        $go = function($forum)use(&$go){
          $forum->extra;
          $forum->link;
          $forum->subfora->each($go);
        };
        
        $go($forum);
        
      });
    
  }
  
}
