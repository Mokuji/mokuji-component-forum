<article>
  
  <header>
    <h2><?php echo $data->topic->title; ?></h2>
    <small>Created <time pubdate datetime="<?php echo $data->starter->dt_created; ?>">at one point</time>.</small>
  </header>
  
  <div class="topic-starter-content">
    <?php echo $data->starter->content; ?>
  </div>
  
  <footer>
    
  </footer>
  
  <!-- Replies. -->
  <?php echo $data->replies; ?>
  
</article>

<?php if(false): ?>
  <?php echo $data->replies; ?>
<?php endif; ?>
