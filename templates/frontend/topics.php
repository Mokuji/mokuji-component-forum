<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

<?php echo load_plugin('jquery_timeago'); ?>

<?php if(!$data->is_god->is_true()): ?>
<div class="forum-banner depth-1">
  <h1 class="forum-title"><?php echo $data->forum->title; ?></h1>
  <p class="forum-description"><?php echo $data->forum->description; ?></p>
</div>
<?php endif; ?>

<?php if($data->subforums->size() > 0): ?>
  
  <?php if(!$data->is_god->is_true()): ?>
    <h3 class="forum-subfora">Subfora</h3>
  <?php endif; ?>
  
  <ol class="forum-banners">
    
    <?php
      foreach($data->subforums as $subforum)
        echo mk('Component')->sections('forum')->get_html('forum_banner', $subforum->merge(array('depth' => $data->forum->depth->get('int'))));
    ?>
    
  </ol>
  
<?php endif; ?>

<h2 class="hidden">Topics</h2>

<?php if(!$data->is_god->is_true()): /*TODO: make a category setting "has_topic" */ ?>
<div class="topic-starter-header clearfix">

  <div class="">
    <a href="<?php echo url('do=edit'); ?>" class="btn btn-small">
      <?php __('forum', 'New topic'); ?>
    </a>
  </div>

</div>
<?php endif; ?>

<?php if($data->topics->size() > 0): ?>

<table class="table table-bordered forum-topic-overview" cellspacing="1">
  <thead>
    <tr>
      <th>Topic</th>
      <th>Replies</th>
      <th>Last post</th>
      <!-- <th>Views</th> -->
    </tr>
  </thead>
  <tfoot></tfoot>
  <tbody>
    <?php foreach($data->topics as $topic): ?>
    <tr class="forum-topic-list">
      <td class="forum-title">
        <a href="<?php echo $topic->link; ?>"><?php echo $topic->title; ?></a><br />
        <small>
          Started <time pubdate datetime="<?php echo $topic->dt_created; ?>"><?php echo $topic->dt_created; ?></time>,
          by <a class="lastpost-author" href="<?php echo url('?pid=63&menu=44&user='.$topic->author->account->id); ?>"><?php echo $topic->author->account->username; ?></a>
        </small>
      </td>
      <td class="forum-replies">
        <?php echo number_format($topic->extra->num_posts->get()); ?>
      </td>
      <td class="forum-lastpost">
        <small>
          <a class="lastpost" href="<?php echo url('tid='.$topic->id.'&page_number='.($topic->extra->num_pages->get('int') - 1)); ?>#forum-reply-<?php echo $topic->extra->last_post->id; ?>">
            <time pubdate data-ucfirst="true" datetime="<?php echo $topic->extra->last_post->dt_created; ?>"><?php echo $topic->extra->last_post->dt_created; ?></time>
          </a><br>
          by <?php echo $topic->extra->last_post->author->account->username; ?>
        </small>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<script type="text/javascript">
jQuery(function($){
  
  function ucFirst(string) {
    return string.charAt(0).toUpperCase() + string.substring(1);
  }
  
  $('time').each(function(){
    
    var $el = $(this);
    
    var inWords = $.timeago($el.attr('datetime'));
    
    if($el.attr('data-ucfirst') == 'true')
      inWords = ucFirst(inWords);
    
    $el
      .text(inWords)
      .attr('title', $el.attr('datetime'));
    
  });
  
});
</script>