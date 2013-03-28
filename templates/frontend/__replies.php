<section class="forum-topic-replies">
  
  <h1>Replies</h1>
  
  <?php foreach($data->posts as $post): ?>
  
  <article class="forum-topic-reply">
    
    <header class="clearfix">
      <h2><a href="#" rel="author"><?php echo $post->author->username; ?></a></h2>
      <p><time pubdate datetime="<?php echo $post->dt_created; ?>">at one point</time></p>
    </header>
    
    <div class="forum-reply-content">
      <?php echo $post->content; ?>
    </div>
    
    <footer class="clearfix">
      <a class="btn btn-mini btn-submit pull-right">Reageer op dit topic</a>
    </footer>
    
  </article>
  
  
  <?php endforeach; ?>
  
</section>
