<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Json extends \dependencies\BaseComponent
{
  
  //Creates a new forum post in the given topic under the currently logged in user.
  public function create_post($data, $parameters)
  {
    
    //Check if the user is logged in.
    if(!tx('Account')->user->check('login')){
      throw new \exception\Authorisation("You can't create forum posts when not logged in.");
    }
    
    #TODO: Authorise user write permissions in the associated forum.
    
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

}
