<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

class Actions extends \dependencies\BaseComponent
{
  
  //Creates a new forum post in the given topic under the currently logged in user.
  protected function new_post($data)
  {
    
    //Check if the user is logged in.
    if(!tx('Account')->user->check('login')){
      throw new \exception\Authorisation("You can't create forum posts when not logged in.");
    }
    
    #TODO: Authorise user write permissions in the associated forum.
    
    //Reference interesting variables.
    $uid = tx('Account')->user->id;
    
    //Create the model.
    $this->model('Posts')
    
      //Merge the save-data.
      ->merge($data->having('topic_id', 'parent_id', 'content'))
      
      //Set the user_id.
      ->push('user_id', $uid)
      
      //Save to the database.
      ->save();

    tx('Url')->redirect(url(''));

  }
    
}
