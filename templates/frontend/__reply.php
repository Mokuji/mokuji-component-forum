  <article class="forum-topic-reply clearfix" id="forum-reply-<?php echo $data->id; ?>">
    
    <header class="clearfix">
      <!-- #TODO: Integrate the style attribute into the CSS. -->
      <!-- <h2 style="font-size:1em;margin:auto;padding:auto"> -->
        <div class="span2">
          <a class="forum-reply-author" href="#" rel="author"><?php echo $data->author->username; ?></a>
          <span><small class="visible-phone"> (Subname)</small></span>
        </div>
        
        <div class="span10">
          <time pubdate datetime="<?php echo $data->dt_created; ?>"><?php echo $data->dt_created; ?></time>
          <!-- <a class="btn btn-mini btn-submit pull-right">Quote</a> -->

          <?php if(tx('Account')->check_level(2)): ?>
            <a data-post-id="<?php echo $data->id; ?>" class="btn-delete-post btn btn-mini pull-right">
              <?php __('forum', 'Delete post'); ?>
            </a>
          <?php endif; ?>

        </div>
      <!-- </h2> -->
    </header>
    
    <div class="span12 alpha forum-reply-content">
      <div class="span2 forum-reply-sidebar hidden-phone">
        <div class="span12 sub-title" hidden>
          <small>Subname</small>
        </div>
        <a href="#" class="thumbnail span11 alpha">
          <?php if($data->author->avatar->get()): ?>
          <img src="<?php echo $data->author->avatar; ?>" data-src="<?php echo $data->author->avatar; ?>" alt="">
          <?php endif; ?>
        </a>
      </div>
      <div class="span10 forum-reply-message">
        <!-- TODO: Parse Markdown: https://github.com/michelf/php-markdown -->
        <?php echo nl2br($data->content->get()); ?>
      </div>
    </div>
    
    <footer class="span10 pull-right signature clearfix">
      <div class="inner">
        <p>Signature text / images come here</p>
      </div>
    </footer>
    
  </article>
