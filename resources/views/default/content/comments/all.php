<main>
  <div class="indent-body">
    <div class="flex justify-between mb20">
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
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('meta.comments_desc'); ?>
  </div>
</aside>