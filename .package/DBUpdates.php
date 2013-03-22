<?php namespace components\forum; if(!defined('TX')) die('No direct access.');

//Make sure we have the things we need for this class.
tx('Component')->check('update');
tx('Component')->load('update', 'classes\\BaseDBUpdates', false);

class DBUpdates extends \components\update\classes\BaseDBUpdates
{
  
  protected
    $component = 'forum',
    $updates = array(
    );
  
  public function install_0_1($dummydata, $forced)
  {
    
    //Drop tables.
    if($forced === true){
      tx('Sql')->query('DROP TABLE IF EXISTS `#__forum_forums`');
      tx('Sql')->query('DROP TABLE IF EXISTS `#__forum_forums_to_pages`');
      tx('Sql')->query('DROP TABLE IF EXISTS `#__forum_posts`');
      tx('Sql')->query('DROP TABLE IF EXISTS `#__forum_topics`');
    }
    
    //Create the forums table.
    tx('Sql')->query("
      CREATE TABLE `tx__forum_forums` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `lft` INT(10) NOT NULL,
        `rgt` INT(10) NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `description` TEXT NULL,
        `dt_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      )
      COLLATE='latin1_swedish_ci'
      ENGINE=MyISAM;
    ");
    
    //Create the link table.
    tx('Sql')->query("
      CREATE TABLE `tx__forum_forums_to_pages` (
        `forum_id` INT(10) UNSIGNED NOT NULL,
        `page_id` INT(10) UNSIGNED NOT NULL,
        PRIMARY KEY (`forum_id`, `page_id`)
      )
      COLLATE='latin1_swedish_ci'
      ENGINE=MyISAM;
    ");
    
    //Create the posts table.
    tx('Sql')->query("
      CREATE TABLE `tx__forum_posts` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `topic_id` INT(10) UNSIGNED NOT NULL,
        `user_id` INT(10) UNSIGNED NOT NULL,
        `parent_id` INT(10) UNSIGNED NULL DEFAULT NULL,
        `content` TEXT NOT NULL,
        `dt_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      )
      COLLATE='latin1_swedish_ci'
      ENGINE=MyISAM;
    ");
    
    //Create the topics table.
    tx('Sql')->query("
      CREATE TABLE `tx__forum_topics` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `forum_id` INT(10) UNSIGNED NOT NULL,
        `post_id` INT(10) UNSIGNED NOT NULL,
        `user_id` INT(10) UNSIGNED NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `properties` SET('STICKY','HOT') NULL DEFAULT NULL,
        `state` ENUM('OPEN','LOCKED','DELETED') NOT NULL DEFAULT 'OPEN',
        `dt_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      )
      COLLATE='latin1_swedish_ci'
      ENGINE=MyISAM;
    ");
    
    //Queue self-deployment with CMS component.
    $this->queue(array('component' => 'cms', 'min_version' => '2.0'), function($version)use($forced){
      
      #TEMP: Disabled until the feature below is released in Beta. (expected in Beta 1.2.0)
      // tx('Component')->helpers('cms')->_call('ensure_pagetypes', array(
      //   array(
      //     'name' => 'forum',
      //     'title' => 'Forum component'
      //   ),
      //   array(
      //     'forum' => false
      //   )
      // ));
      
      #TEMP: The following code can be replaced by the above code.
      $c = tx('Sql')->model('cms', 'Components')->set(array(
        'name' => 'forum',
        'title' => 'Forum component'
      ))->save();
      tx('Sql')->model('cms', 'ComponentViews')->set(array(
        'com_id' => $c->id,
        'name' => 'forum',
        'tk_title' => 'FORUM_VIEW_TITLE',
        'tk_description' => 'FORUM_VIEW_DESCRIPTION',
        'thumbnail' => 'NULL',
        'is_config' => '0'
      ))
      ->save();
      
    });
    
  }
  
}
