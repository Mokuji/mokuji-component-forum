<?php if($data->subforums->size() > 0): ?>

  <h2 class="hidden">Sub forums</h2>

    <table class="table table-bordered forum-subforum-overview">
      <thead>
        <tr>
          <th>Forum</th>
          <th>Last post</th>
          <!-- <th>Topics</th>
          <th>Posts</th> -->
        </tr>
      </thead>
      <tfoot></tfoot>
      <tbody>  
        <?php foreach($data->subforums as $forum): ?>
        <tr class="forum-subforum-list">
          <td class="forum-title">
            <a href="<?php echo $forum->link; ?>"><?php echo $forum->title; ?></a>
          </td>
          <td class="forum-lastpost">
            <?php /*
            <a href="#"><?php echo $forum->extra->last_post->topic_title; ?></a><br />
            <small><time pubdate datetime="<?php echo $forum->extra->last_post->dt_created; ?>"><?php echo $forum->extra->last_post->dt_created; ?></time><!-- by <a class="lastpost-author" href="#"><?php echo $data->last_post->user_id; ?></a>--></small>
            */ ?>
          </td>
          <!--
          <td class="forum-topics">
            2.503
          </td>
          <td class="forum-posts">
            658.200
          </td>
        -->
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
<?php endif; ?>

<h2 class="hidden">Topics</h2>

<?php if(true): /*TODO: make a category setting "has_topic" */ ?>
<div class="topic-starter-header clearfix">

  <div class="pull-right">
    <a href="<?php echo url('do=edit'); ?>" class="btn btn-small pull-right">
      <?php __('forum', 'New topic'); ?>
    </a>
  </div>

</div>
<?php endif; ?>

<?php if($data->topics->size() > 0): ?>

<table class="table table-bordered forum-topic-overview">
  <thead>
    <tr>
      <th>Topic</th>
      <th>Last post</th>
      <th>Replies</th>
      <th>Views</th>
    </tr>
  </thead>
  <tfoot></tfoot>
  <tbody>  
    <?php foreach($data->topics as $topic): ?>
    <tr class="forum-topic-list">
      <td class="forum-title">
        <!-- <div class="span7"> -->
          <a href="<?php echo $topic->link; ?>"><?php echo $topic->title; ?></a><br />
          <small><time pubdate datetime="<?php echo $topic->dt_created; ?>"><?php echo $topic->dt_created; ?></time>, by <a class="lastpost-author" href="<?php echo url('?pid=63&menu=44&user='.$topic->author->account->id); ?>"><?php echo $topic->author->account->username; ?></a></small>
        <!-- </div> -->
        <!--
        <div class="pagination pagination-mini pagination-right pull-right hidden-phone">
          <ul>
            <li><a href="#">Prev</a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">Next</a></li>
          </ul>
        </div>
      -->
      </td>
      <td class="forum-lastpost">
        <?php /*
        <small><time pubdate datetime="<?php echo $topic->extra->last_post->dt_created; ?>"><?php echo $topic->extra->last_post->dt_created; ?></time> by <a class="lastpost-author" href="#"><?php echo $topic->extra->last_post->user_id; ?></a></small>
        */ ?>
      </td>
      <td class="forum-replies">
        <?php
        /*
        echo $topic->extra->num_posts; */ ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
