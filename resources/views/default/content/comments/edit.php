<?= insert('/_block/add-js-css'); ?>

<main>
  <a href="/"><?= __('app.home'); ?></a> / <span class="gray-600"><?= __('app.edit_comment'); ?>:</span>
  <a class="mb5 block" href="<?= post_slug($data['post']['post_id'], $data['post']['post_slug']); ?>"><?= $data['post']['post_title']; ?></a>

  <form class="max-w780" action="<?= url('content.change', ['type' => 'comment']); ?>" accept-charset="UTF-8" method="post">
    <?= csrf_field() ?>

    <?= insert('/_block/form/editor', ['height'  => '300px', 'content' => $data['comment']['comment_content'], 'type' => 'comment', 'id' => $data['post']['post_id']]); ?>

    <?php if (UserData::checkAdmin()) : ?>
      <?= insert('/_block/form/select/user', ['user' => $data['user']]); ?>
    <?php endif; ?>

    <input type="hidden" name="comment_id" value="<?= $data['comment']['comment_id']; ?>">
    <?= Html::sumbit(__('app.edit')); ?>
    <a href="<?= post_slug($data['post']['post_id'], $data['post']['post_slug']); ?>#comment_<?= $data['comment']['comment_id']; ?>" class="text-sm inline ml15 gray"><?= __('app.cancel'); ?></a>
  </form>
</main>