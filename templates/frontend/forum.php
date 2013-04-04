<?php echo load_plugin('bootstrap'); ?>

<?php echo $data->breadcrumbs; ?>

<div class="tx-forum row-fluid alpha">
<?php echo $data->section; ?>
</div>

<?php echo load_plugin('jquery_timeago'); ?>

<script>
  $(function(){
    $('time').text(function(){
      var dt = new Date($(this).attr('datetime'));
      return dt.toLocaleDateString() + ' ' + dt.toLocaleTimeString();
    }).timeago();
  });
</script>
