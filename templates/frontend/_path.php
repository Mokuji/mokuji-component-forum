<div class="breadcrumbs">
  <?php foreach($data->path as $node): ?>
  <span> &raquo; </span>
  <a href="<?php echo $node->link; ?>"><?php echo $node->title; ?></a>
  <?php endforeach; ?>
</div>
