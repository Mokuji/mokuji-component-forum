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
    
    $posts = $this
      ->table('Posts')
      ->where('topic_id', $this->id)
      ->order('dt_created', 'DESC')
      ->execute();

    return array(
      'last_post' => $posts->{0},
      'num_posts' => ($posts->size()-1)
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
  
}
