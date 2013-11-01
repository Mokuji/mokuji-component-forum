<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

<section class="forum-overview">
  
  <h1>Forums</h1>
  <ul class="forum-overview nolist overview-list">
    <?php foreach($data->forums as $forum): ?>
    <li class="forum-overview-item<?php echo $forum->extra->has_unread_posts->is_true() ? ' unread' : ''; ?>">
      <a href="<?php echo $forum->link; ?>"><span class="forum-unread-icon"></span><?php echo $forum->title; ?></a>
    </li>
    <?php endforeach; ?>
  </ul>
  
</section>

<script type="text/javascript">

$(function(){
  
  // window.location = $('.forum-overview-item a').attr('href');
  
});

</script>
