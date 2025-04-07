<?php $item = $data['content']; ?>
<main class="w-100">
  <div class="box">
    <h2 class="mt5"><?= __('app.edit_' . $item['post_type']); ?></h2>
    <form class="mb20" action="<?= url('edit.' . $item['post_type'], method: 'post'); ?>" method="post" enctype="multipart/form-data">
      <?= $container->csrf()->field(); ?>
      <?= insert('/content/publications/edit/edit-form-' . $item['post_type'], ['item' => $item, 'data' => $data]); ?>
    </form>
    <div class="box-info">
      <?= __('help.edit_' . $item['post_type']); ?>
    </div>
  </div>
</main>

<script src="/assets/js/tag/tagify.min.js"></script>
<link rel="stylesheet" href="/assets/js/tag/tagify.css" type="text/css">
<script src="/assets/js/cropper/cropper.min.js"></script>
<link rel="stylesheet" href="/assets/js/cropper/cropper.min.css" type="text/css">