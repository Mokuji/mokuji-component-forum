<?php echo load_plugin('bootstrap'); ?>

<?php echo $data->breadcrumbs; ?>

<div class="tx-forum row-fluid alpha">
<?php echo $data->section; ?>
</div>

<?php echo load_plugin('jquery_timeago'); ?>

<script>
  
  //On DOM-ready.
  $(function(){
    
    //Query for all <time>-elements.
    $('time')
    
    //Set the text in them based on their "datetime" attribute.
    .text(function(){
      var dt = new Date($(this).attr('datetime'));
      return dt.toLocaleDateString() + ' ' + dt.toLocaleTimeString();
    })
    
    //Active the jQuery TimeAgo plugin on them.
    .timeago();
    
  });
  
</script>
