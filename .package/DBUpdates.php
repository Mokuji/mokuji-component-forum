<?php namespace components\forum; if(!defined('MK')) die('No direct access.');

//Make sure we have the things we need for this class.
mk('Component')->check('update');

class DBUpdates extends \components\update\classes\BaseDBUpdates
{
  
  protected
    $component = 'forum',
    $updates = array(
      '0.1' => '0.0.2-alpha'
    );
  
  public function update_to_0_0_2_alpha($current_version, $forced)
  {
    
    if($forced === true){
      mk('Sql')->query('DROP TABLE IF EXISTS `#__forum_topic_last_reads`');
      mk('Sql')->query('DROP TABLE IF EXISTS `#__forum_topic_subscriptions`');
    }
    
    mk('Sql')->query("
      CREATE TABLE `#__forum_topic_last_reads` (
        `topic_id` int(10) unsigned NOT NULL,
        `user_id` int(10) unsigned NOT NULL,
        `dt_last_read` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`topic_id`, `user_id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
    
    mk('Sql')->query("
      CREATE TABLE `#__forum_topic_subscriptions` (
        `topic_id` int(10) unsigned NOT NULL,
        `user_id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`topic_id`, `user_id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
    
  }
  
  public function install_0_1($dummydata, $forced)
  {
    
    //Drop tables.
    if($forced === true){
      mk('Sql')->query('DROP TABLE IF EXISTS `#__forum_forums`');
      mk('Sql')->query('DROP TABLE IF EXISTS `#__forum_forums_to_pages`');
      mk('Sql')->query('DROP TABLE IF EXISTS `#__forum_posts`');
      mk('Sql')->query('DROP TABLE IF EXISTS `#__forum_topics`');
    }
    
    //Create the forums table.
    mk('Sql')->query("
      CREATE TABLE `#__forum_forums` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `lft` INT(10) NOT NULL,
        `rgt` INT(10) NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `description` TEXT NULL,
        `dt_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
    
    //Create the link table.
    mk('Sql')->query("
      CREATE TABLE `#__forum_forums_to_pages` (
        `forum_id` INT(10) UNSIGNED NOT NULL,
        `page_id` INT(10) UNSIGNED NOT NULL,
        PRIMARY KEY (`forum_id`, `page_id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
    
    //Create the posts table.
    mk('Sql')->query("
      CREATE TABLE `#__forum_posts` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `topic_id` INT(10) UNSIGNED NOT NULL,
        `user_id` INT(10) UNSIGNED NOT NULL,
        `parent_id` INT(10) UNSIGNED NULL DEFAULT NULL,
        `content` TEXT NOT NULL,
        `dt_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
    
    //Create the topics table.
    mk('Sql')->query("
      CREATE TABLE `#__forum_topics` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `forum_id` INT(10) UNSIGNED NOT NULL,
        `post_id` INT(10) UNSIGNED NULL,
        `user_id` INT(10) UNSIGNED NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `properties` SET('STICKY','HOT') NULL DEFAULT NULL,
        `state` ENUM('OPEN','LOCKED','DELETED') NOT NULL DEFAULT 'OPEN',
        `dt_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
    
    //Queue self-deployment with CMS component.
    $this->queue(array('component' => 'cms', 'min_version' => '2.0'), function($version)use($forced){
      
      #TEMP: Disabled until the feature below is released in Beta. (expected in Beta 1.2.0)
      // mk('Component')->helpers('cms')->_call('ensure_pagetypes', array(
      //   array(
      //     'name' => 'forum',
      //     'title' => 'Forum component'
      //   ),
      //   array(
      //     'forum' => false
      //   )
      // ));
      
      #TEMP: The following code can be replaced by the above code.
      $c = mk('Sql')->model('cms', 'Components')->set(array(
        'name' => 'forum',
        'title' => 'Forum component'
      ))->save();
      mk('Sql')->model('cms', 'ComponentViews')->set(array(
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
