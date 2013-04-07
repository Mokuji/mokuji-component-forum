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
  
  //Generate a URL to this forum.
  public function get_link()
  {
    
    return url("?pid=KEEP&rfid=KEEP&fid=".$this->__get('id'), true);
    
  }

  //Get extra forum info.
  public function get_extra()
  {
    
    return array(
      'last_post' => $this
        ->table('Posts')
        ->join('Topics', $T)
        ->select("$T.title", 'topic_title')
        ->where("$T.forum_id", $this->id)
        ->order('dt_created', 'DESC')
        ->limit(1)
        ->execute_single(),
      'num_topics' => 0,
      'num_posts' => 0
    );
    
  }
  
}
