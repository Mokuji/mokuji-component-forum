<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

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

      <input type="text" class="title-field" name="title" placeholder="<?php __('forum', 'Topic title'); ?>" />

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
      localStorageName: 'forum_topic_reply_drafts_epiceditor',
      file:{
        name: 'topic_new',
        autosave: 1000
      },
      theme: {
        editor: '/themes/editor/epic-light.css',
        preview: '../../../../bootstrap/css/bootstrap.min.css'
      },
      button: {
        fullscreen: false
      }
    });
    
    //Place it in the DOM.
    editor.load(function(){
      
      //Now that we are sure there were no javascript errors, hide the textarea.
      $('#<?php echo $form_id; ?>-textarea-topic').hide();
      
      //Enable CTRL + Enter submitting
      $(editor.getElement('editor')).on('keydown', function(e){
        if(e.ctrlKey === true && e.keyCode === 13)
          $('.forum-topic-form form').trigger('submit');
      });
      
    });
    
    window.onresize = function () {
      editor.reflow();
    }
    
    $('.forum-topic-form form').restForm({
      success: function(data){
        editor.remove('topic_new');
        document.location = "<?php echo url(''); ?>&tid="+data.id;
      }
    });
    
  });
  
</script>
