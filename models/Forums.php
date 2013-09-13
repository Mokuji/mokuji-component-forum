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
    
    return url("?pid=KEEP&menu=KEEP&rfid=KEEP&fid=".$this->__get('id'), true);
    
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
        ->execute_single(),
      'num_topics' => mk('Sql')
        ->table('forum', 'Topics')
        ->where('forum_id', $this->id)
        ->count(),
      'num_posts' => mk('Sql')
        ->table('forum', 'Posts')
        ->join('Topics', $T)
        ->where("$T.forum_id", $this->id)
        ->count()
    );
    
  }
  
  public function get_subfora()
  {
    
    return mk('Sql')
      ->table('forum', 'Forums')
      ->parent_pk($this->id)
      ->add_relative_depth()
      ->max_depth(1)
      ->execute();
    
  }
  
  //Override, also deleting posts and threads.
  public function hdelete()
  {
    
    //Recursively delete forum contents.
    $go = function($forum)use(&$go){
      
      //Delete topics (this deletes the posts).
      mk('Sql')
        ->table('forum', 'Topics')
        ->where("forum_id", $forum->id)
        ->execute()
        ->each(function($topic){
          $topic->delete();
        });
      
      //Recursive.
      $forum->subfora->each($go);
      
    };
    
    //Bombs away!
    $go($this);
    
    //Now delete the forum and it's children.
    parent::hdelete();
    
  }
  
}
