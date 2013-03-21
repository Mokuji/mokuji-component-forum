<?php namespace components\forum\models; if(!defined('TX')) die('No direct access.');

class Forums extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'forum_forums',
    
    $relations = array(
      'PageLink' => array('id' => 'PageLink.forum_id'),
      'Topics' => array('id' => 'Topics.forum_id')
    ),
    
    $validate = array(
      'id' => array('required', 'number'=>'int', 'gt'=>0),
      'title' => array('required', 'string', 'no_html', 'between'=>array(0, 255))
    ),
    
    $hierarchy = array(
      'left' => 'lft',
      'right' => 'rgt'
    );
  
  
  
}
