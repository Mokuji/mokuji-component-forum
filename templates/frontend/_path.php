<ul class="breadcrumb">
  <?php foreach($data->path as $node): ?>
  <li>
    <a href="<?php echo $node->check('last') ? '#' : $node->link; ?>"><?php echo $node->title; ?></a>
    <?php if(!$node->check('last')): ?>
    <span class="divider">/</span>
    <?php endif;?>
  </li>
  <?php endforeach; ?>
</ul>
