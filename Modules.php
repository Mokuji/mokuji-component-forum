<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Modules extends \dependencies\BaseViews
{
  
  protected $permissions = array(
    'path' => 0,
    'user_information' => 0
  );
  
  //Return a small user information header.
  protected function user_information()
  {
    
    return array(
      'account' => mk('Account')->is_login() ?
        mk('Sql')
          ->table('account', 'Accounts')
          ->pk(mk('Account')->user->id)
          ->execute_single()
          ->without('password', 'salt', 'hashing_algorithm') : null,
      'register_link' => url('menu=KEEP&pid=KEEP&register=true', true),
      'edit_profile_link' => url('menu=KEEP&pid=KEEP&edit_profile=true', true),
      'login_link' => url('login=true&register=NULL'),
      'logout_link' => url('action=account/logout')
    );
    
  }
  
  //Generate a breadcrumb path.
  protected function path($data)
  {
    
    //Reference interesting variables.
    $pid = $data->pid->otherwise(tx('Data')->get->pid);
    $fid = $data->fid->otherwise(tx('Data')->get->fid);
    $rfid = $data->rfid->otherwise(tx('Data')->get->rfid);
    $tid = $data->tid->otherwise(tx('Data')->get->tid);
    
    //Get the forum ID based on topic ID.
    if($tid->is_set()){
      $topic = $this->table('Topics')->pk($tid)->execute_single();
      $fid = $topic->forum_id;
    }
    
    //Get deepest node.
    if($fid->is_set()){
      $deepest = $this->table('Forums')
        ->pk($fid)
        ->execute_single();
    }
    
    //No deepest node.
    else{
      $deepest = Data(null);
    }
    
    //Get the root node based on root forum ID.
    if($rfid->is_set()){
      $root = $this->table('Forums')
        ->pk($rfid)
        ->execute_single();
    }
    
    //Get the root node based on page ID, combined with a deeper node trace.
    elseif($pid->is_set() && $deepest->is_set()){
      $root = $this->table('Forums', $F)
        ->join('PageLink', $PL)
        ->where("$PL.page_id", $pid)
        ->add_hierarchy()
        ->where('lft', '<=', $deepest->lft)
        ->where('rgt', '>=', $deepest->rgt)
        ->execute_single();
    }
    
    //Get the root node based on page ID only.
    elseif($pid->is_set()){
      $root = $this->table('Forums')
        ->join('PageLink', $PL)
        ->where("$PL.page_id", $pid)
        ->execute_single();
    }
    
    //No root node.
    else{
      $root = Data(null);
    }
    
    //Begin the list by adding the "Home"-node.
    //No, begin the list with the root node. If available.
    $path = Data();
    
    //Only generate the path of forums if we can start somewhere.
    if($root->is_set())
    {
      
      //No deepest node?
      if($deepest->is_empty())
      {
        
        //Push the only node into the path array.
        $path->push(array(
          'title' => $root->title,
          'link' => url("?pid=KEEP&rfid=KEEP&fid={$root->id}", true)
        ));
        
      }
      
      //We have a deepest node.
      else
      {
        
        //Get all nodes in between the root and the deepest.
        $between = $this->table('Forums')
          ->add_hierarchy()
          ->parent_pk(true, $root->id)
          ->where('lft', '<=', $deepest->lft)
          ->where('rgt', '>=', $deepest->rgt)
          ->execute();
        
        //Add them all to the path array.
        $between->each(function($val)use($path){
          $path->push(array(
            'title' => $val->title,
            'link' => url("?pid=KEEP&rfid=KEEP&fid={$val->id}", true)
          ));
        });

      }
      
    }
    
    //Add the topic to the path array?
    if($tid->is_set()){
      $path->push(array(
        'title' => $topic->title,
        'link' => url("?pid=KEEP&rfid=KEEP&fid=KEEP&tid={$topic->id}", true)
      ));
    }
    
    //Add additional data.
    $i = 0; $total = $path->size();
    $path->each(function($node)use(&$i, &$total){
      $node->first = ($i === 0);
      $i++;
      $node->last = ($i === $total);
    });
    
    //Return template data.
    return array(
      'path' => $path
    );
    
  }
  
}
