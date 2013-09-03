<section class="forum-overview">
  
  <h1>Forums</h1>
  <ul class="forum-overview nolist overview-list">
    <?php foreach($data->forums as $forum): ?>
    <li class="forum-overview-item">
      <a href="<?php echo $forum->link; ?>"><?php echo $forum->title; ?></a>
    </li>
    <?php endforeach; ?>
  </ul>
  
</section>

<script type="text/javascript">

$(function(){
  
  window.location = $('.forum-overview-item a').attr('href');
  
});

</script>
