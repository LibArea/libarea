<?php $post = $data['post']; ?>
<main>
  <div class="box">
    <h2 class="m0"><?= __('app.edit_' . $post['post_type']); ?></h2>

    <form class="max-w-md" action="<?= url('edit.post', ['type' => $post['post_type']], method: 'post'); ?>" method="post" enctype="multipart/form-data">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/edit-post', ['post' => $post, 'data' => $data]); ?>
    </form>
  </div>
</main>
<aside>
  <div class="box">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.edit_' . $post['post_type']); ?>
  </div>
</aside>

<script src="/assets/js/tag/tagify.min.js"></script>
<link rel="stylesheet" href="/assets/js/tag/tagify.css" type="text/css">
<script src="/assets/js/cropper/cropper.min.js"></script>
<link rel="stylesheet" href="/assets/js/cropper/cropper.min.css" type="text/css">