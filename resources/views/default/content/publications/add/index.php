<main class="w-100">
  <div class="box">
    <h2 class="mt5"><?= __('app.add_' . $data['type']); ?></h2>
    <form class="mb20" action="<?= url('add.' . $data['type'], method: 'post'); ?>" method="post" enctype="multipart/form-data">
      <?= $container->csrf()->field(); ?>
      <?= insert('/content/publications/add/add-form-' . $data['type'], ['data' => $data]); ?>
    </form>
    <div class="box-info">
      <?= __('help.add_' . $data['type']); ?>
    </div>
  </div>
</main>

<script src="/assets/js/tag/tagify.min.js"></script>
<link rel="stylesheet" href="/assets/js/tag/tagify.css" type="text/css">
<script src="/assets/js/cropper/cropper.min.js"></script>
<link rel="stylesheet" href="/assets/js/cropper/cropper.min.css" type="text/css">