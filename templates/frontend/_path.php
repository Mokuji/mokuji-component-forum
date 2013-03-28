<ul class="breadcrumb">
  <?php foreach($data->path as $node): ?>
  <li>
    <a href="<?php echo $node->link; ?>"><?php echo $node->title; ?></a>
    <span class="divider">/</span>
  </li>
  <?php endforeach; ?>
</ul>
