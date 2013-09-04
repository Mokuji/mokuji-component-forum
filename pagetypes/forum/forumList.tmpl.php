<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

{{each(i, forum) data}}
<li class="forum" data-forum-id="${forum.id}">
  <div class="info">
    <div class="meta">
      <span class="title">${forum.title}</span>
      <span class="description">${forum.description}</span>
    </div>
    <div class="operations">
      <a href="<?php echo URL_BASE; ?>?pid=${page_id}&fid=${forum.id}" target="_blank" class="icon-eye-open view-forum"></a>
      <a href="#" class="icon-pencil edit-forum"></a>
      <a href="#" class="icon-remove delete-forum"></a>
    </div>
    <div class="extra">
      {{if forum.extra.last_post}}
        <a href="<?php echo URL_BASE; ?>?pid=${page_id}&fid=${forum.id}&tid=${forum.extra.last_post.topic_id}" class="last_post">
          <?php __($component, 'Last post'); ?>: ${forum.extra.last_post.topic_title}
        </a>
      {{/if}}
      <span class="topics"><?php __($component, 'Topics'); ?>: ${forum.extra.num_topics}</span><br>
      <span class="posts"><?php __($component, 'Posts'); ?>: ${forum.extra.num_posts}</span>
    </div>
  </div>
  <form method="PUT" action="?rest=forum/forum/${forum.id}" class="edit-sub-forum-form hidden">
    <div class="ctrlHolder">
      <label><?php __($component, 'Edit sub forum'); ?></label><br>
      <input type="text" class="title" name="title" placeholder="<?php __('Title'); ?>" value="${forum.title}" /><br>
      <textarea class="description" name="description" placeholder="<?php __('Description'); ?>">${forum.description}</textarea><br>
      <input type="submit" class="button black" value="<?php __('Save'); ?>"/>
    </div>
  </form>
  {{if forum.subfora}}<ol>{{html template('forumList', forum.subfora)}}</ol>{{/if}}
</li>
{{/each}}