<article class="topic-starter-topic">
  
  <header class="clearfix">
    <h1><?php echo $data->topic->title; ?></h1>
    <p><small>Created <time pubdate datetime="<?php echo $data->starter->dt_created; ?>">at one point</time>.</small></p>
  </header>
  
  <div class="topic-starter-content">
    <?php echo $data->starter->content; ?>
  </div>
  
  <footer class="clearfix">
    <a class="btn btn-mini btn-submit pull-right">Reageer op dit topic</a>
  </footer>
  
</article>

<!-- Replies. -->
<?php echo $data->replies; ?>


<?php if(false): ?>
  <?php echo $data->replies; ?>
<?php endif; ?>
