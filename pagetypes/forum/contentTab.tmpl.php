<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

<form method="PUT" action="?rest=forum/page_forum" id="forum-contentTab-form" class="form">
  
  <input type="hidden" name="id" value="${data.id}" />
  <input type="hidden" name="page_id" value="${page_id}" />
  
  <div class="ctrlHolder">
    <input type="text" class="title" name="title" placeholder="<?php __('Title'); ?>" value="${data.title}" /><br>
    <textarea class="description" name="description" placeholder="<?php __('Description'); ?>">${data.description}</textarea>
  </div>
  
</form>

<div class="sub-fora-operations">
  <a href="#" class="add-sub-forum"><?php __($component, 'Add sub forum') ?></a>
</div>

<form method="POST" action="?rest=forum/forum" id="forum-createForum-form" class="form hidden">
  <div class="ctrlHolder">
    <label>New sub forum</label>
    <input type="text" class="title" name="title" placeholder="<?php __('Title'); ?>" /><br>
    <textarea class="description" name="description" placeholder="<?php __('Description'); ?>"></textarea><br>
    <input type="submit" class="button black" value="<?php __('Add'); ?>"/>
  </div>
</form>

<!-- ${page_id} -->

<ol class="sub-fora">{{if data.subfora}}{{html template('forumList', data.subfora)}}{{/if}}</ol>
