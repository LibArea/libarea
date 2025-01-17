<main>
  <div class="nav-bar">
    <ul class="nav">
      <?= insert('/_block/navigation/nav', [
        'list' => [
          [
            'tl'    => 0, // not authorized
            'id'    => 'comments.all',
            'url'   => url('comments'),
            'title' => 'app.comments',
          ],
          [
            'tl'    => 10, // admin
            'id'    => 'comments.deleted',
            'url'   => url('comments.deleted'),
            'title' => 'app.deleted',
          ],
        ]
      ]); ?>
    </ul>
  </div>
  <?php if (!empty($data['comments'])) : ?>
    <?= insert('/content/comments/comment', ['comments' => $data['comments']]); ?>

    <?php $path = ($data['sheet'] == 'deleted') ? url('comments.deleted') : '/comments'; ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, $path); ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'info']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('meta.all_comments_desc'); ?>
  </div>
</aside>