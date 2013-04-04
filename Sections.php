<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{
  
  //Load a single reply, providing the data.
  public function reply($data)
  {
    
    return $data;
    
  }
  
  //Load the reply template, but insert JQuery template tags.
  public function reply_js($data)
  {
    
    return array(
      'author' => array(
        'username' => '${author.username}',
        'subname' => '${author.subname}'
      ),
      'dt_created' => '${dt_created}',
      'content' => '${content}'
    );
    
  }
  
}
