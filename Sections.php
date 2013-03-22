<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{
  
  //Loads a list of forums based on a page ID.
  public function forum_page($data)
  {
    
    //Reference interesting variables.
    $pid = $data->pid->otherwise(tx('Data')->get->pid);
    
    //Validate them.
    $pid->validate('Page ID', array('required', 'number'=>'int', 'gt'=>0));
    
    //Get forums associated with this page.
    $forums = $this->table('Forums')
    ->join('PageLink', $PL)
    ->where("$PL.page_id", $pid)
    ->execute();
    
    //Return template data.
    return array(
      'forums' => $forums
    );
    
  }
  
  //Loads a list of topics and sub-forums based on a forum ID.
  public function forum($data)
  {
    
    //Reference interesting variables.
    $fid = $data->fid->otherwise(tx('Data')->get->fid);
    
    //Validate them.
    $fid->validate('Forum ID', array('required', 'number'=>'int', 'gt'=>0));
    
    //Get sub-forums.
    $subforums = $this->table('Forums')
    ->parent_pk($fid)
    ->add_relative_depth()
    ->max_depth(1)
    ->execute();
    
    //Get topics.
    $topics = $this->table('Topics')
    ->where('forum_id', $fid)
    ->execute();
    
    //Return template data.
    return array(
      'subforums' => $subforums,
      'topics' => $topics
    );
    
  }
  
  //Loads a list of posts based on a topic ID.
  public function topic($data)
  {
    
    //Reference interesting variables.
    $tid = $data->tid->otherwise(tx('Data')->get->tid);
    
    //Validate them.
    $tid->validate('Topic ID', array('required', 'number'=>'int', 'gt'=>0));
    
    //Get topic information.
    $topic = $this->table('Topics')
    ->pk($tid)
    ->execute_single();
    
    //Get topic-starter.
    $starter = $this->table('Posts')
    ->pk($topic->post_id)
    ->execute_single();
    
    //Get replies.
    $replies = $this->table('Posts')
    ->where('topic_id', $tid)
    ->where('id', '!', $topic->post_id)
    ->execute();
    
    //Return template data.
    return array(
      'topic' => $topic,
      'starter' => $starter,
      'replies' => $replies
    );
    
  }
  
}
