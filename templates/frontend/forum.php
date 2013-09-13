<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

<?php echo load_plugin('bootstrap'); ?>

<div class="tx-forum-meta clearfix">
  <?php echo $data->user_information; ?>
  <?php echo $data->breadcrumbs; ?>
</div>

<div class="tx-forum">
  <?php echo $data->section; ?>
</div>

