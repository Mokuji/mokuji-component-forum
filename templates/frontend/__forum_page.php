<h2>Forums</h2>
<ul>
  <?php foreach($data->forums as $forum): ?>
  <li><a href="<?php echo $forum->link; ?>"><?php echo $forum->title; ?></a></li>
  <?php endforeach; ?>
</ul>
