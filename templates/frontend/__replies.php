<section class="forum-topic-replies">
  
  <h1>Replies</h1>
  
  <?php foreach($data->posts as $post): ?>
  
  <article class="forum-topic-reply clearfix">
    <header class="clearfix">
      <div class="span3">
        <a class="forum-reply-author" href="#" rel="author"><?php echo $post->author->username; ?></a>
      </div>
      
      <div class="span9">
        <time pubdate datetime="<?php echo $post->dt_created; ?>">at one point</time>
        <a class="btn btn-mini btn-submit pull-right">Quote</a>
      </div>
    </header>
    
    <div class="span12 alpha forum-reply-content">
      <div class="span3 forum-reply-sidebar hidden-phone">
        <div class="span12 sub-title">  
          <small>Subname</small>
        </div>
        <a href="#" class="thumbnail span8 alpha">
          <img src="http://placehold.it/200x200" data-src="http://placehold.it/75x75" alt="">
        </a>
      </div>
      <div class="span9 forum-reply-message">
        <?php echo $post->content; ?>
      </div>
    </div>
    
    
    <footer class="span9 pull-right signature clearfix">
      <div class="inner">
        <p>Signature text / images come here</p>
      </div>
    </footer>
  </article>
  
  
  <?php endforeach; ?>
  
</section>
