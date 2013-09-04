<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Helpers extends \dependencies\BaseViews
{
  
  //Get replies to a given topic.
  public function get_replies($tid, $offset=0, $per_page=null)
  {
    
    $resultset = $this->table('Posts', $P)
      ->where('topic_id', $tid)
      ->join('Topics', $T)
      ->where("$T.post_id", '!', "`$P.id`")
      ->order('dt_created')
      ->limit($this->_per_page($per_page), $offset)
      ->execute();
    
    $resultset->idx($resultset->size()-1)->merge(array('is_latest' => true));
    
    return $resultset;
    
  }

  //Get forum info.
  public function get_forum($fid)
  {
    
    return $this->table('Forums', $F)
    ->pk($fid)
    ->execute_single();
    
  }
  
  //Return the offset based on a page number.
  public function calc_offset($page_number, $per_page=null)
  {
    
    return (1
      * (Data($page_number)->otherwise(0)->get('int'))
      * ($this->_per_page($per_page))
    );
    
  }
  
  //Calculate and return the amount of pages needed to contain the given amount of posts.
  public function calc_pagecount($num_posts, $per_page=null)
  {
    
    return (int) ceil(1
      * max(1, Data($num_posts)->otherwise(1)->get('int'))
      / ($this->_per_page($per_page))
    );
    
  }
  
  //Get posts per page.
  private function _per_page($override = null)
  {
    
    return max(1, Data($override)->otherwise(tx('Data')->session->per_page)->otherwise(20)->get('int'));
    
  }
  
}
