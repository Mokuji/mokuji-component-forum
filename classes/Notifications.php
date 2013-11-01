<?php namespace components\forum\classes; if(!defined('MK')) die('No direct access.');

abstract class Notifications
{
  
  public static function user_notification($user_id, $subject, $message)
  {
    
    raw($user_id, $subject, $message);
    
    //Check for the component.
    if(!mk('Component')->available('mail')){
      mk('Logging')->log('Forum', 'Notification', 'Could not send user notification. Mail component is not available.');
      return false;
    }
    
    //Initialize the markdown parser.
    load_plugin('php_markdown');
    $parser = new \Michelf\MarkdownExtra();
    $parser->no_markup = true;
    
    //Parse it now.
    $html_message = $parser->transform($message);
    
    //Keep track of status.
    $return = true;
    
    //Now send the message.
    mk('Component')->helpers('mail')->send_fleeting_mail(array(
      'to'=>mk('Account')->user->email,
      'subject'=>$subject,
      'html_message'=>$html_message
    ))
    
    ->failure(function($info)use(&$return){
      $e = $info->exception;
      mk('Logging')->log('Forum', 'Notification', n.$e->getMessage().n.'in '.$e->getFile().'('.$e->getLine().')'.n.$e->getTraceAsString(), false, true);
      $return = false;
    });
    
    return $return;
    
  }
  
}
