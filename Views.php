<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{
  
  //Loads a forum.
  public function forum($data)
  {
    
    #TODO: Claim account.
    
    //Reference interesting variables.
    $pid = tx('Data')->get->pid;
    $fid = tx('Data')->get->fid;
    $tid = tx('Data')->get->tid;
    
    //Load a topic based on topic-id?
    if($tid->is_set()){
      $section = $this->section('topic', array('tid' => $tid));
    }
    
    //Load a forum based on forum-id?
    elseif($fid->is_set()){
      $section = $this->section('forum', array('fid' => $fid));
    }
    
    //Load a list of forums based on page-id?
    elseif($pid->is_set()){
      $section = $this->section('forum_page', array('fid' => $fid));
    }
    
    //Otherwise redirect to the administrators panel.
    else{
      $section = null;
      tx('Url')->redirect('/admin/', true);
    }
    
    //Return template data.
    return array(
      'section' => $section
    );
    
  }
  
}
