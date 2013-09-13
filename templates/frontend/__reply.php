<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

  <article class="forum-topic-reply clearfix" id="forum-reply-<?php echo $data->id; ?>">
    
    <?php if($data->check('is_latest')): ?>
      <a href="#" id="latest"></a>
    <?php endif; ?>
    
    <header class="clearfix">
      <!-- #TODO: Integrate the style attribute into the CSS. -->
      <!-- <h2 style="font-size:1em;margin:auto;padding:auto"> -->
        
        <a href="<?php echo url('?pid=63&menu=44&user='.$data->author->account->id); ?>" class="thumbnail avatar">
          <?php if($data->author->account->user_info->avatar->get()): ?>
          <img src="<?php echo $data->author->account->user_info->avatar->generate_url(array('fill_width' => 64,'fill_height' => 64));; ?>" data-src="<?php echo $data->author->account->user_info->avatar->generate_url(array('fill_width' => 180,'fill_height' => 180));; ?>" alt="">
          <?php endif; ?>
        </a>
        
        <div class="">
          <a class="forum-reply-author" href="<?php echo url('?pid=63&menu=44&user='.$data->author->account->id); ?>" rel="author"><?php echo $data->author->account->username; ?></a>
          <small class="forum-reply-author-subtitle">&nbsp;<?php echo $data->author->title; ?></small>
          <small class="forum-reply-dt-posted">
            Posted <time pubdate datetime="<?php echo $data->dt_created; ?>"><?php echo $data->dt_created; ?></time>
          </small>
        </div>
        
        <?php if(tx('Account')->check_level(2)): ?>
          <div class="forum-topic-reply-operations">
            <small>
              <a data-post-id="<?php echo $data->id; ?>" class="btn-delete-post" href="#">
                <?php __('forum', 'Delete post'); ?>
              </a>
            </small>
          </div>
        <?php endif; ?>
      <!-- </h2> -->
    </header>
    
    <div class="forum-reply-content">
      <div class="forum-reply-message">
        <?php echo $data->content->get(); ?>
      </div>
    </div>
    
    <?php if($data->author->signature->is_set()): ?>
    <footer class="signature clearfix">
      <div class="inner">
        <small>
          <p><?php echo $data->author->signature; ?></p>
        </small>
      </div>
    </footer>
    <?php endif; ?>
    
  </article>
