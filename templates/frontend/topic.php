<!-- Topic-starter. -->
<section class="topic-starter-topic">
  
  <div class="topic-starter-header clearfix">
    <h1 class="topic-starter-title span7"><?php echo $data->topic->title; ?></h1>
    <div class="pagination pull-right span5">
      
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
  </div>
  
  <div<?php if($data->show_starter->get('bool') !== true) echo ' hidden'; ?>>
  <?php echo tx('Component')->sections('forum')->get_html('reply', $data->starter); ?>
  </div>
  
</section>

<!-- Replies. -->
<section class="forum-topic-replies">
  
  <h1>Replies</h1>
  
  <?php foreach($data->replies as $reply): ?>
  <?php echo tx('Component')->sections('forum')->get_html('reply', $reply); ?>
  <?php endforeach; ?>
  
</section>

<!-- Reply form. -->
<!-- #TODO -->
