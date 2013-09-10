<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Sections extends \dependencies\BaseViews
{
  
  protected $permissions = array(
    'reply' => 0,
    'forum_banner' => 0,
    'reply_js' => 0
  );
  
  public function forum_banner($data)
  {
    
    return $data
      ->merge(array(
        'depth' => $data->depth->otherwise(1)
      ));
    
  }
  
  //Load a single reply, providing the data.
  public function reply($data)
  {
    
    $data->content->is('set', function($content){
      
      //Initialize the markdown parser.
      load_plugin('php_markdown');
      $parser = new \Michelf\MarkdownExtra();
      $parser->no_markup = true;
      
      //Parse it now.
      $content->set(
        $parser->transform($content->get())
      );
      
    });
    
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
