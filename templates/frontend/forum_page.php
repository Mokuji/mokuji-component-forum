<section class="forum-overview">
  
  <h1>Forums</h1>
  <ul class="forum-overview">
    <?php foreach($data->forums as $forum): ?>
    <li><a href="<?php echo $forum->link; ?>"><?php echo $forum->title; ?></a></li>
    <?php endforeach; ?>
  </ul>
  
</section>
