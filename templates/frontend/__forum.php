<h2>Sub forums</h2>
<ul>
  <?php foreach($data->subforums as $forum): ?>
  <li><a href="<?php echo $forum->link; ?>"><?php echo $forum->title; ?></a></li>
  <?php endforeach; ?>
</ul>

<h2>Topics</h2>
<ul>
  <?php foreach($data->topics as $topic): ?>
  <li><a href="<?php echo $topic->link; ?>"><?php echo $topic->title; ?></a></li>
  <?php endforeach; ?>
</ul>
