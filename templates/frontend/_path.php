<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

<ul class="breadcrumb">
  <?php foreach($data->path as $node): ?>
  <li>
    <a href="<?php echo $node->link; ?>"><?php echo $node->title; ?></a>
    <?php if(!$node->check('last')): ?>
    <span class="divider">/</span>
    <?php endif;?>
  </li>
  <?php endforeach; ?>
</ul>
