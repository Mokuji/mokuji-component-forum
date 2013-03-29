<article class="topic-starter-topic">
  <div class="topic-starter-header clearfix">
    <h1 class="topic-starter-title span7"><?php echo $data->topic->title; ?></h1>
    <div class="pagination pull-right span5">
      <ul>
        <li class="disabled"><a href="#">&laquo;</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">&raquo;</a></li>
      </ul>
    </div>
  </div>
  
  <div class="topic-starter-wrapper clearfix">
    
  
    <header class="clearfix">
      <div class="span3">
        <a class="forum-topic-author" href="#" rel="author"><?php echo $data->starter->author->username; ?></a>
      </div>
      
      <div class="span9">
        <small>Created <time pubdate datetime="<?php echo $data->starter->dt_created; ?>">at one point</time>.
        <a class="btn btn-mini btn-submit pull-right">Reply</a>
      </div>
    </header>
    
    <div class="span12 alpha topic-starter-content">
      <div class="span3 topic-starter-sidebar hidden-phone">
        <div class="span12 sub-title">  
          <small>Subname</small>
        </div>
        <a href="#" class="thumbnail span8 alpha">
          <img src="http://placehold.it/200x200" data-src="http://placehold.it/75x75" alt="">
        </a>
      </div>
      <div class="span9 topic-starter-message">
        <?php echo $data->starter->content; ?>
      </div>
    </div>
    
    <footer class="span9 pull-right signature clearfix">
      <div class="inner">
        <p>Signature text / images come here</p>
      </div>
    </footer>
    
  </div>  
</article>

<!-- Replies. -->
<?php echo $data->replies; ?>


<?php if(false): ?>
  <?php echo $data->replies; ?>
<?php endif; ?>
