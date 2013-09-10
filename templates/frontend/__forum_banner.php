<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

<li class="forum-banner depth-<?php echo $data->depth; ?>">
  
  <div class="forum-information-wrapper">
    
    <?php
      $head = 'h'.$data->depth;
      echo "<$head class=\"forum-title\"><a href=\"{$data->link}\">{$data->title}</a></$head>";
    ?>
    
    <p class="forum-description"><?php echo $data->description; ?></p>
    
    <div class="forum-meta">
      <span class="forum-topic-count">Topics: <?php echo $data->extra->num_topics; ?></span>
      <span class="forum-post-count">Posts: <?php echo $data->extra->num_posts; ?></span>
    </div>
    
    <div class="forum-lastpost">
      
      <?php
        
        if($data->extra->last_post->is_set()){
          $lastpost = $data->extra->last_post;
          $lasttopic = $lastpost->topic;
          $lasttopic_url = url('tid='.$lasttopic->id);
          $lastpost_url = url('tid='.$lasttopic->id.'&page_number='.($lasttopic->extra->num_pages->get('int') - 1)).'#forum-reply-'.$lastpost->id;
          
          ?>
            <div class="forum-lastpost-author-wrap" title="Authored by <?php echo $lastpost->author->account->username; ?>">
              
              <span class="forum-lastpost-label-last-post">Last post</span>
              <span class="forum-lastpost-label-by">by</span>
              <a class="forum-lastpost-author" href="#"><?php echo $lastpost->author->account->username; ?></a>
              
              <span class="forum-lastpost-label-in">in</span>
              <a class="forum-lastpost-title" href="<?php echo $lasttopic_url; ?>"><?php echo str_max($lasttopic->title->get(), $data->depth->get() < 2 ? 50 : 30, '&hellip;'); ?></a>
              
            </div>
            
            <span class="forum-lastpost-label-timedash">-</span>
            <a href="<?php echo $lastpost_url; ?>">
              <time pubdate datetime="<?php echo $lastpost->dt_created; ?>"><?php echo $lastpost->dt_created; ?></time>.
            </a>
          <?php
          
        }
        
        else{
          
          ?>
          <span class="forum-lastpost-none">No posts yet.</span>
          <?php
          
        }
        
      ?>
      
    </div>
    
  </div>
  
  <?php if(!$data->subfora->is_empty()){ ?>
    
    <ol class="forum-banners subfora">
      
      <?php
        foreach($data->subfora as $subforum)
          echo mk('Component')->sections('forum')->get_html('forum_banner', $subforum->merge(array('depth' => $data->depth->get('int') + 1)));
      ?>
      
    </ol>
    
  <? } ?>
  
</li>