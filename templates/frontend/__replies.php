<section>
  
  <h2>Replies</h2>
  
  <?php foreach($data->posts as $post): ?>
  
  <article>
    
    <header>
      <h3><?php echo $post->author->username; ?></h3>&nbsp;&nbsp;&nbsp;
      <time pubdate datetime="<?php echo $post->dt_created; ?>">at one point</time>
    </header>
    
    <?php echo $post->content; ?>
    
  </article>
  
  
  <?php endforeach; ?>
  
</section>
