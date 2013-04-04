<!-- Topic-starter. -->
<section id="topic-starter" class="topic-starter-topic">
  
  <div class="topic-starter-header clearfix">
    <h1 class="topic-starter-title span7"><?php echo $data->topic->title; ?></h1>
    <div class="pagination pull-right">
      
      <ul>
        
        <li<?php if($data->pager->check('first_page')) echo ' class="disabled"'; ?>>
          <a href="<?php echo $data->pager->link_first; ?>">&laquo;</a>
        </li>
        
        <?php foreach($data->pager->pages as $page): ?>
        <li<?php if($page->check('active')) echo ' class="active"'; ?>>
          <a href="<?php echo $page->link; ?>"><?php echo $page->nr; ?></a>
        </li>
        <?php endforeach; ?>
        
        <li<?php if($data->pager->check('last_page')) echo ' class="disabled"'; ?>>
          <a href="<?php echo $data->pager->link_last; ?>">&raquo;</a>
        </li>
        
      </ul>
      
    </div>
    
    <!-- #TODO: Make this button nice. -->
    <div class="pull-right span1" style="margin-right:10px">
      <a data-actions="focus-reply-form scroll-reply-form" class="btn pull-right" href="#reply-form">React</a>
    </div>
    
  </div>
  
  <div<?php if($data->show_starter->get('bool') !== true) echo ' hidden'; ?>>
    <?php echo tx('Component')->sections('forum')->get_html('reply', $data->starter); ?>
  </div>
  
</section>

<!-- Replies. -->
<section id="replies" class="forum-topic-replies">
  
  <h1>Replies</h1>
  
  <?php foreach($data->replies as $reply): ?>
  <?php echo tx('Component')->sections('forum')->get_html('reply', $reply); ?>
  <?php endforeach; ?>
  
</section>

<!-- Reply form. -->
<section id="reply-form">
  
  <h1>Reply</h1>
  
  <form method="POST" action="<?php echo url('?action=forum/new_post'); ?>">
    
    <textarea name="content"></textarea>
    <input name="submit" type="submit" />
    
  </form>
  
</section>
