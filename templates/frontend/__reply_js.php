<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

<?php echo tx('Component')->sections('forum')->get_html('reply', $data); ?>
<?php header('Content-Type: text/x-jquery-tmpl'); ?>
