<?php namespace components\forum\models; if(!defined('TX')) die('No direct access.');

class Posts extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'forum_posts',
    
    $relations = array(
      'Topics' => array('topic_id' => 'Topics.id'),
      'Forums' => array('topic_id' => 'Topics.id'),
      'Accounts' => array('user_id' => 'account.Accounts.id'),
      'Posts' => array('parent_id' => 'Posts.id')
    ),
    
    $validate = array(
      'id' => array('required', 'number'=>'int', 'gt'=>0),
      'topic_id' => array('required', 'number'=>'int', 'gt'=>0),
      'user_id' => array('required', 'number'=>'int', 'gt'=>0),
      'parent_id' => array('number'=>'int', 'gt'=>0)
    );
  
  //A cache for Account objects.
  private $authors = [];
  
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
