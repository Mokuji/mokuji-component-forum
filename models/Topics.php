<?php namespace components\forum\models; if(!defined('TX')) die('No direct access.');

use \components\forum\classes\Notifications;

class Topics extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'forum_topics',
    
    $relations = array(
      'Forums' => array('forum_id' => 'Forums.id'),
      'Posts' => array('post_id' => 'Posts.id'),
      'Accounts' => array('user_id' => 'account.Accounts.id'),
    ),
    
    $validate = array(
      'id' => array('required', 'number'=>'int', 'gt'=>0),
      'forum_id' => array('required', 'number'=>'int', 'gt'=>0),
      'post_id' => array('required', 'number'=>'int', 'gt'=>0),
      'user_id' => array('required', 'number'=>'int', 'gt'=>0),
      'title' => array('required', 'string', 'no_html', 'between'=>array(0, 255))
    );
  
  //Generate a URL to this topic.
  public function get_link()
  {
    
    return url("?pid=KEEP&rfid=KEEP&fid=KEEP&tid=".$this->__get('id'), true);
    
  }

  //Get extra topic info.
  public function get_extra()
  {
    
    //Get 1 model.
    $last_post = $this
      ->table('Posts')
      ->where('topic_id', $this->id)
      ->order('dt_created', 'DESC')
      ->execute_single();
    
    //The -1 seems to be so the thread starter post is excluded.
    $num_posts = $this
      ->table('Posts')
      ->where('topic_id', $this->id)
      ->count()->get('int') - 1;
    
    //Skip when not logged in.
    if(!mk('Account')->check_level(1)){
      $unread = false;
      $subscribed = false;
    }
    else{
      $last_read = mk('Sql')->table('forum', 'TopicLastReads')
        ->where('topic_id', $this->id)
        ->where('user_id', mk('Account')->user->id)
        ->execute_single()
        ->dt_last_read->get();
      $unread = strtotime($last_post->dt_created->get()) > strtotime($last_read);
      $subscribed = mk('Sql')->table('forum', 'TopicSubscriptions')
        ->where('topic_id', $this->id)
        ->where('user_id', mk('Account')->user->id)
        ->count()->get('int') > 0;
    }
    
    return array(
      'has_unread_posts' => $unread,
      'is_subscribed' => $subscribed,
      'last_post' => $last_post,
      'num_posts' => $num_posts,
      'num_pages' => mk('Component')->helpers('forum')->calc_pagecount($num_posts)
    );
    
  }

  //Return the Account object associated with the author of this post.
  public function get_author()
  {
    
    //Get the author ID.
    $aid = $this->__get('user_id')->get('int');
    
    //Check the cache.
    if(array_key_exists($aid, $this->authors)){
      return $this->authors[$aid];
    }
    
    //Do the query.
    $author = tx('Sql')->table('community', 'UserProfiles')->where('user_id', $aid)->execute_single();
    
    //Cache the result.
    $this->authors[$aid] = $author;
    
    //Return the result.
    return $author;
    
  }

  public function bump_read()
  {
    
    //Skip when not logged in.
    if(!mk('Account')->check_level(1))
      return $this;
    
    mk('Sql')->model('forum', 'TopicLastReads')
      ->set(array(
        'topic_id' => $this->id,
        'user_id' => mk('Account')->user->id,
        'dt_last_read' => date('Y-m-d H:i:s')
      ))
      ->save();
    
    return $this;
    
  }
  
  public function subscribe()
  {
    
    //Skip when not logged in.
    if(!mk('Account')->check_level(1))
      return $this;
    
    mk('Sql')->model('forum', 'TopicSubscriptions')
      ->set(array(
        'topic_id' => $this->id,
        'user_id' => mk('Account')->user->id
      ))
      ->save();
    
    return $this;
    
  }
  
  public function unsubscribe()
  {
    
    //Skip when not logged in.
    if(!mk('Account')->check_level(1))
      return $this;
    
    mk('Sql')->table('forum', 'TopicSubscriptions')
      ->pk(array(
        'topic_id' => $this->id,
        'user_id' => mk('Account')->user->id
      ))
      ->execute_single()
      ->is('set', function($sub){
        $sub->delete();
      });
    
    return $this;
    
  }
  
  /**
   * Sends a 'new reply' notification to all subscribers.
   * @param  int  $exclude The reply author should be excluded from the notifications, you can enter their user ID here.
   * @return self For chaining.
   */
  public function notify_subscribers($exclude=0)
  {
    
    raw($exclude);
    $topic = $this;
    
    mk('Sql')->table('forum', 'TopicSubscriptions')
      ->where('topic_id', $this->id)
      ->where('user_id', '!', intval($exclude))
      ->execute()
      ->each(function($sub)use($topic){
        $extra = Data($topic->get_extra());
        Notifications::user_notification($sub->user_id, "New reply on {$topic->title}",
"{$extra->last_post->content}

[View the reply](".url($topic->link.'&page_number='.$extra->num_pages, true).'#latest'.")

[View the topic]({$topic->link})

(You can unsubscribe from this topic on the topic page)"
        );
      });
    
    return $this;
    
  }
  
  public function delete()
  {
    
    //First delete all posts.
    mk('Sql')
      ->table('forum', 'Posts')
      ->where('topic_id', $this->id)
      ->execute()
      ->each(function($post){
        $post->delete();
      });
    
    //Now delete the thread.
    parent::delete();
    
  }
  
}
