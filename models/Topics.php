<?php namespace components\forum\models; if(!defined('TX')) die('No direct access.');

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
    
    return array(
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
