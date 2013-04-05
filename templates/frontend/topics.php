<?php if($data->subforums->size() > 0): ?>

  <h2 class="hidden">Sub forums</h2>

    <table class="table table-bordered forum-subforum-overview">
      <thead>
        <tr>
          <th>Forum</th>
          <th>Last post</th>
          <th>Topics</th>
          <th>Posts</th>
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
            <a href="#">Last post title here</a><br />
            <small><time pubdate datetime="<?php echo $data->dt_created; ?>">at one point</time> by <a class="lastpost-author" href="#">1337LutZ</a></small>
          </td>
          <td class="forum-topics">
            2.503
          </td>
          <td class="forum-posts">
            658.200
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
<?php endif; ?>

<?php if($data->topics->size() > 0): ?>
<h2 class="hidden">Topics</h2>

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
        <div class="span7">
          <a href="<?php echo $topic->link; ?>"><?php echo $topic->title; ?></a><br />
          <small><time pubdate datetime="<?php echo $data->dt_created; ?>">at one point</time> by <a class="lastpost-author" href="#">1337LutZ</a></small>
        </div>
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
      </td>
      <td class="forum-lastpost">
        <a href="#">Last post title here</a>
        <small><time pubdate datetime="<?php echo $data->dt_created; ?>">at one point</time> by <a class="lastpost-author" href="#">1337LutZ</a></small>
      </td>
      <td class="forum-views">
        5.842
      </td>
      <td class="forum-replies">
        356
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
