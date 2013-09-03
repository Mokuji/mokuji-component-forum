<?php namespace components\forum\models; if(!defined('TX')) die('No direct access.');

class PageLink extends \dependencies\BaseModel
{
  
  protected static
    
    $table_name = 'forum_forums_to_pages',
    
    $relations = array(
      'Forums' => array('forum_id' => 'Forums.forum_id'),
      'Pages' => array('page_id' => 'cms.Pages.id')
    ),
    
    $validate = array(
      'forum_id' => array('required', 'number'=>'int', 'gt'=>0),
      'page_id' => array('required', 'number'=>'int', 'gt'=>0)
    );
  
  
  
}
