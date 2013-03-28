<?php echo $data->breadcrumbs; ?>
<?php echo $data->section; ?>

<?php echo load_plugin('jquery_timeago'); ?>

<script>
  $(function(){
    $('time').text(function(){
      var dt = new Date($(this).attr('datetime'));
      return dt.toLocaleDateString() + ' ' + dt.toLocaleTimeString();
    }).timeago();
  });
</script>
