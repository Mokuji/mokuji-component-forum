<?php

//Load plugins.
echo load_plugin('jquery_rest');

//Create an unique forum identifier.
$form_id = 'form'.tx('Security')->random_string(10);

?>

<!-- New/Edit topic form. -->
<section class="forum-topic-form span12 alpha">
  
  <form method="POST" action="<?php echo url('?rest=forum/topic'); ?>" id="<?php echo $form_id; ?>">

    <fieldset class="span12 alpha">

      <legend><?php __('forum', 'New topic'); ?></legend>
      
      <input type="hidden" name="forum_id" value="<?php echo $data->forum->id; ?>" />

      <input type="text" name="title" placeholder="<?php __('forum', 'Topic title'); ?>" />

      <div class="control-group">
        <textarea id="<?php echo $form_id; ?>-textarea-topic" rows="10" class="input-block-level markdownarea" name="content" placeholder=""></textarea>
        <div id="epiceditor"></div>
      </div>
      <div class="control-group button-set pull-right">
        <!-- <button class="btn btn btn-link" id="btn-preview">Preview</button> -->
        <input class="btn btn-inverse" name="submit" type="submit" value="<?php __('forum', 'Post topic'); ?>" />
      </div>

    </fieldset>

  </form>
  
</section>

<script>
  $(function(){
    
    //Create a new EpicEditor and pass the options.
    var editor = new EpicEditor({
      textarea: '<?php echo $form_id; ?>-textarea-topic',
      theme: {
        editor: '/themes/editor/epic-light.css',
        preview: '../../../../bootstrap/css/bootstrap.min.css'
      },
      clientSideStorage: false
    });
    
    //Place it in the DOM.
    editor.load();

    window.onresize = function(){
      setTimeout(function(){
        editor.reflow('width');
      }, 1000);
    }
    
  });

  $(function(){
    $('.forum-topic-form form').restForm({
      success: function(data){
        document.location = "<?php echo url(''); ?>&tid="+data.id;
      }
    });
  });

</script>
