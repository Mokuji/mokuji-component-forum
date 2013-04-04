<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{
  
  //Loads a forum.
  public function forum()
  {
    
    #TODO: Claim account.
    
    //Reference interesting variables.
    $pid = tx('Data')->get->pid;
    $fid = tx('Data')->get->fid;
    $tid = tx('Data')->get->tid;
    
    //Load a topic based on topic-id?
    if($tid->is_set()){
      $section = $this->view('topic');
    }
    
    //Load a forum based on forum-id?
    elseif($fid->is_set()){
      $section = $this->section('forum');
    }
    
    //Load a list of forums based on page-id?
    elseif($pid->is_set()){
      $section = $this->section('forum_page');
    }
    
    //Otherwise redirect to the administrators panel.
    else{
      $section = null;
      tx('Url')->redirect('/admin/', true);
    }
    
    //Get the breadcrumb-path.
    $breadcrumbs = $this->module('path');
    
    //Return template data.
    return array(
      'breadcrumbs' => $breadcrumbs,
      'section' => $section
    );
    
  }
  
  //Loads a list of posts based on a topic ID.
  public function topic()
  {
    
    //Reference interesting variables.
    $tid = tx('Data')->get->tid;
    $page_number = tx('Data')->get->page_number->get('int');
    $offset = $this->helper('calc_offset', $page_number);
    
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
    $replies = $this->helper('get_replies', $tid, $offset);
    
    //Get the total number of replies.
    $num_replies = $this->table('Posts', $P)
    ->where('topic_id', $tid)
    ->join('Topics', $T)
    ->where("$T.post_id", '!', "`$P.id`")
    ->count();
    
    //Calculate total pages.
    $total_pages = $this->helper('calc_pagecount', $num_replies);
    $last_page = ($total_pages-1);
    
    //Get pages.
    // $P = '`#__forum_posts`';
    // $pages = tx('Sql')->execute_query("
    //   SELECT p.* FROM (
    //     SELECT * FROM $P
    //     ORDER BY 
    //   )
    // ")
    // SELECT r.*
    //   FROM ( 
    //          SELECT *
    //            FROM mbr_qa_questions
    //           ORDER BY q_votes DESC
    //        ) r
    //  CROSS
    //   JOIN ( SELECT @i := 0 ) s
    // HAVING ( @i := @i + 1) MOD 3 = 1
    $pages = Data(array());
    for($i = 0; $i < $total_pages; $i++){
      $pages->push(array(
        'nr' => ($i+1),
        'first' => ($i===0),
        'last' => ($i===$total_pages),
        'active' => ($i===$page_number),
        'link' => url("?page_number=$i")
      ));
    }
    
    //Return template data.
    return array(
      'topic' => $topic,
      'starter' => $starter,
      'replies' => $replies,
      'show_starter' => ($offset == 0),
      'pager' => array(
        'pages' => $pages,
        'first_page' => ($page_number === 0),
        'last_page' => ($page_number === $last_page),
        'link_first' => url("?page_number=0"),
        'link_last' => url("?page_number=$last_page")
      )
    );
    
  }
  
}
