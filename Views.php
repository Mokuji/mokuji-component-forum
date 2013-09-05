<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Views extends \dependencies\BaseViews
{
  
  protected $permissions = array(
    'forum' => 0,
    'forum_page' => 0,
    'topics' => 0,
    'topic' => 0,
    'topic_edit' => 0
  );
  
  //Loads a forum.
  public function forum($options)
  {
    
    #TODO: Claim account.
    
    //Reference interesting variables.
    $register = tx('Data')->get->register->validate('Register', array('boolean'));
    $profile  = tx('Data')->get->edit_profile->validate('Edit profile', array('boolean'));
    $pid      = $options->pid->value->otherwise(tx('Data')->get->pid);
    $fid      = tx('Data')->get->fid;
    $tid      = tx('Data')->get->tid;
    $edit     = tx('Data')->get->do == 'edit';
    
    //Check the sanity of the page number.
    if(mk('Data')->get->page_number->get('int') < 0){
      mk('Url')->redirect('page_number=NULL');
      return;
    }
    
    if($register->is_true())
    {
      
      //Gets a registration form for us.
      $view = mk('Component')
        ->modules('account')
        ->get_html('register', array(
          'target_url'=>url('register=NULL&edit_profile=true')
        ));
      
    }
    
    elseif($profile->is_true())
    {
      
      //Gets a registration form for us.
      $view = '<div class="clearfix">';
      $view .= mk('Component')->views('account')->get_html('profile');
      $view .= mk('Component')->views('community')->get_html('profile', array('edit'=>true));
      $view .= '</div>';
      
    }
    
    //Load a topic based on topic-id?
    elseif($tid->is_set()){
      $view = $this->view('topic');
    }
    
    //Load the create-a-new-topic view?
    elseif($fid->is_set() && $edit){
      $view = $this->view('topic_edit');
    }
    
    //Load a forum based on forum-id?
    elseif($fid->is_set()){
      $view = $this->view('topics');
    }
    
    //Load a forum based on page-id?
    elseif($pid->is_set()){
      
      //Get forum associated with this page.
      $forum = $this->table('Forums')
        ->join('PageLink', $PL)
        ->where("$PL.page_id", $pid)
        ->execute_single();
      
      $view = $this->view('topics', array('fid'=>array('key'=>'fid', 'value'=>$forum->id->get())));
      
    }
    
    //Otherwise redirect to the administrators panel.
    else{
      $view = null;
      tx('Url')->redirect('/admin/', true);
    }
    
    //Get the breadcrumb-path.
    $breadcrumbs = $this->module('path');
    $user_information = $this->module('user_information');
    
    //Return template data.
    return array(
      'breadcrumbs' => $breadcrumbs,
      'user_information' => $user_information,
      'section' => $view
    );
    
  }
  
  //Loads a list of forums based on a page ID.
  public function forum_page($options)
  {
    
    //Reference interesting variables.
    $pid = $options->pid->value->otherwise(tx('Data')->get->pid);
    
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
  public function topics($options)
  {
    
    //Reference interesting variables.
    $fid = $options->fid->value->otherwise(tx('Data')->get->fid);
    
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
      ->order('dt_created', 'DESC')
      ->execute();
    
    //Return template data.
    return array(
      'subforums' => $subforums,
      'topics' => $topics
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
    $new_page_on_reply = $this->helper('calc_pagecount', $num_replies->get('int')+1) > $total_pages;
    
    //Check the sanity of the page number.
    if(mk('Data')->get->page_number->get('int') > $last_page){
      mk('Url')->redirect('page_number='.$last_page);
      return;
    }
    
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
    
    //Load required includes into the output buffer.
    tx('Ob')->add(load_plugin('epiceditor'), 'script', 'epiceditor');
    #TODO: Load script from includes. -- tx('Ob')->add('<script src=""></script>', 'script', 'topic');
    
    //Return template data.
    return array(
      'topic' => $topic,
      'starter' => $starter,
      'replies' => $replies,
      'show_starter' => ($offset == 0),
      'show_reply' => (tx('Account')->user->check('login') /*#TODO: && check_write_permission() */),
      'pager' => array(
        'pages' => $pages,
        'first_page' => ($page_number === 0),
        'last_page' => ($page_number === $last_page),
        'new_page_on_reply' => $new_page_on_reply,
        'link_first' => url("?page_number=0"),
        'link_last' => url("?page_number=$last_page"),
        'link_after_reply' => url("?page_number=".($new_page_on_reply ? $last_page+1 : $last_page))
      )
    );
    
  }
 
  public function topic_edit($data)
  {

    //Reference interesting variables.
    $pid = tx('Data')->get->pid;
    $fid = tx('Data')->get->fid;
    $tid = tx('Data')->get->tid;

    //Validate.
    $fid->validate('Forum ID', array('required', 'number'=>'int', 'gt'=>0));

    //Load required includes into the output buffer.
    tx('Ob')->add(load_plugin('epiceditor'), 'script', 'epiceditor');
    #TODO: Load script from includes. -- tx('Ob')->add('<script src=""></script>', 'script', 'topic');

    return array(
      'forum' => $this->helper('get_forum', $fid)
    );

  }

}
