<?php

//Load plugins.
echo load_plugin('jquery_rest');

//Reference user, for easy access.
$user = &tx('Account')->user;

//Create an unique forum identifier.
$form_id = 'form'.tx('Security')->random_string(10);

?>

<!-- Topic-starter. -->
<section id="topic-starter" class="topic-starter-topic">
  
  <div class="topic-starter-header clearfix">
    <h1 class="topic-starter-title span7"><?php echo $data->topic->title; ?></h1>
    <div class="pagination pagination-small pull-right" style="margin-left:10px">
      
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
    <div class="pull-right">
      
      <?php if(tx('Account')->check_level(2)): ?>
      <a data-topic-id="<?php echo $data->topic->id; ?>" class="btn-delete-topic btn btn-small pull-right">
        <?php __('forum', 'Delete topic'); ?>
      </a>
      <?php endif; ?>

      <a data-actions="focus-reply-form scroll-reply-form" class="btn btn-small pull-right" href="#reply-form">
        <?php __('forum', 'React'); ?>
      </a>
    </div>
    
  </div>
  
  <div<?php if(!$data->check('show_starter')) echo ' hidden'; ?>>
    <?php echo tx('Component')->sections('forum')->get_html('reply', $data->starter); ?>
  </div>
  
</section>

<!-- Replies. -->
<section id="replies" class="forum-topic-replies">
  
  <h1><?php __('forum', 'Replies'); ?></h1>
  
  <?php foreach($data->replies as $reply): ?>
  <?php echo tx('Component')->sections('forum')->get_html('reply', $reply); ?>
  <?php endforeach; ?>
  
</section>

<?php if($data->check('show_reply')): ?>

<!-- Reply form. -->
<section id="reply-form" class="forum-topic-reply-form span12 alpha">
  
  <form method="POST" action="<?php echo url('?rest=forum/post'); ?>" id="<?php echo $form_id; ?>">

    <fieldset class="span12 alpha">

      <legend><?php __('forum', 'Reply'); ?></legend>
      
      <input type="hidden" name="topic_id" value="<?php echo $data->topic->id; ?>" />

      <div class="hidden-phone span2 alpha"></div>
      
      <div class="span10">
        <div class="control-group">
          <textarea id="<?php echo $form_id; ?>-textarea-new-post" rows="10" class="input-block-level markdownarea" name="content" placeholder="Enter message here"></textarea>
          <div id="epiceditor"></div>
        </div>
        <div class="control-group button-set pull-right">
          <!-- <button class="btn btn btn-link" id="btn-preview">Preview</button> -->
          <input class="btn btn-inverse" name="submit" type="submit" value="<?php __('forum', 'Reply'); ?>" />
        </div>
      </div>
    </fieldset>

  </form>
  
</section>

<script>
  $(function(){
    
    //Create a new EpicEditor and pass the options.
    var editor = new EpicEditor({
      textarea: '<?php echo $form_id; ?>-textarea-new-post',
      theme: {
        editor: '/themes/editor/epic-light.css',
        preview: '../../../../bootstrap/css/bootstrap.min.css'
      },
      clientSideStorage: false
    });
    
    //Place it in the DOM.
    editor.load();

    window.onresize = function () {
      editor.reflow();
    }
    
  });

  $(function(){
    $('#reply-form form').restForm({
      success: function(data){
        document.location = "<?php echo url(''); ?>";
      }
    });
  });

  <?php if(tx('Account')->check_level(2)): ?>

    //Delete topic.
    $(function(){
      $('.btn-delete-topic').on('click', function(e){

        e.preventDefault();

        if(confirm('Are you sure you want to delete this topic?')){
          $.rest('GET', "<?php echo url('?rest=forum/delete_topic/',1); ?>"+$(e.target).data('topic-id'))
            .done(function(result){
              document.location = "<?php echo url('pid=KEEP&fid=KEEP',1); ?>";
            });
        }

      });
    });

    //Delete post.
    $(function(){
      $('.btn-delete-post').on('click', function(e){

        e.preventDefault();

        var that = $(this);

        if(confirm('Are you sure you want to delete this post?')){
          $.rest('GET', "<?php echo url('?rest=forum/delete_post/',1); ?>"+$(e.target).data('post-id'))
            .done(function(result){
              $(that).closest('.forum-topic-reply').slideUp();
            });
        }

      });
    });

  <?php endif; ?>

</script>

<?php endif; ?>
