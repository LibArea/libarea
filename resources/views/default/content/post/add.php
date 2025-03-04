<main>
  <div class="box">
    <h2 class="title"><?= __('app.add_post'); ?></h2>

    <form class="max-w-md" action="<?= url('add.post', ['type' => 'post'], method: 'post'); ?>" method="post" enctype="multipart/form-data">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/add-post', ['data' => $data]); ?>
    </form>
  </div>
</main>
<aside>
  <div class="box">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.add_post'); ?>
  </div>
</aside>

<script src="/assets/js/tag/tagify.min.js"></script>
<link rel="stylesheet" href="/assets/js/tag/tagify.css" type="text/css">
<script src="/assets/js/cropper/cropper.min.js"></script>
<link rel="stylesheet" href="/assets/js/cropper/cropper.min.css" type="text/css">