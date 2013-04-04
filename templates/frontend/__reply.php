  <article class="forum-topic-reply clearfix">
    
    <header class="clearfix">
      <!-- #TODO: Integrate the style attribute into the CSS. -->
      <!-- <h2 style="font-size:1em;margin:auto;padding:auto"> -->
        <div class="span3">
          <a class="forum-reply-author" href="#" rel="author"><?php echo $data->author->username; ?></a>
          <span><small class="visible-phone"> (Subname)</small></span>
        </div>
        
        <div class="span9">
          <time pubdate datetime="<?php echo $data->dt_created; ?>">at one point</time>
          <a class="btn btn-mini btn-submit pull-right">Quote</a>
        </div>
      <!-- </h2> -->
    </header>
    
    <div class="span12 alpha forum-reply-content">
      <!-- #TODO: Integrate the style attribute into the CSS. -->
      <div class="span3 forum-reply-sidebar hidden-phone" style="max-width:300px">
        <div class="span12 sub-title">  
          <small>Subname</small>
        </div>
        <a href="#" class="thumbnail span8 alpha">
          <img src="http://placehold.it/200x200" data-src="http://placehold.it/75x75" alt="">
        </a>
      </div>
      <div class="span9 forum-reply-message">
        <?php echo $data->content; ?>
      </div>
    </div>
    
    <footer class="span9 pull-right signature clearfix">
      <div class="inner">
        <p>Signature text / images come here</p>
      </div>
    </footer>
    
  </article>
