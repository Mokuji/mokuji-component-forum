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

<?php if($data->check('show_reply')): ?>
  
<!-- Reply form. -->
<section id="reply-form" class="forum-topic-reply-form span12 alpha">
  
  <form method="POST" action="<?php echo url('?action=forum/new_post/post'); ?>">

    <fieldset class="span12 alpha">

      <legend><h1>Reply</h1></legend>
      
      <input type="hidden" name="topic_id" value="<?php echo $data->topic->id; ?>" />

      <div class="hidden-phone span2 alpha"></div>
      
      <div class="span10">
        <div class="control-group">
          <textarea id="textarea-new-post" rows="10" class="input-block-level" name="content" placeholder="Enter message here" hidden></textarea>
          <div id="epiceditor"></div>
        </div>
        <div class="control-group button-set pull-right">
          <!-- <button class="btn btn btn-link" id="btn-preview">Preview</button> -->
          <input class="btn btn-inverse" name="submit" type="submit" value="Reply" />
        </div>
      </div>
    </fieldset>

  </form>
  
</section>

<script>
  (function(){
    
    //Create a new EpicEditor and pass the options.
    var editor = new EpicEditor({
      textarea: 'textarea-new-post',
      theme: {
        editor: '/themes/editor/epic-light.css',
        preview: '/themes/bootstrap.min.css'
      }
    });
    
    //Place it in the DOM.
    editor.load();
    
  })()
</script>

<?php endif; ?>
