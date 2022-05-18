<?php $post = $data['post']; ?>
<main>
  <div class="box">
    <h2><?= __('app.edit_' . $post['post_type']); ?></h2>

    <form class="max-w780" id="editPost" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?= component('edit-post', ['post' => $post, 'data' => $data]); ?>
    </form>

  </div>
</main>
<aside>
  <div class="box">
    <h3 class="uppercase-box"><?= __('app.help'); ?></h3>
    <?= __('help.edit_' . $post['post_type']); ?>
  </div>
</aside>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('content.change', ['type' => $post['post_type']]),
    'redirect'  => '/post/' . $post['post_id'],
    'success'   => __('msg.successfully'),
    'id'        => 'form#editPost'
  ]
); ?>