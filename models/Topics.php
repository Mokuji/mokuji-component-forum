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
  
  
  
}
