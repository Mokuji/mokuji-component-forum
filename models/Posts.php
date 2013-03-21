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
  
  
  
}
