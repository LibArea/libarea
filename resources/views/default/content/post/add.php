<?= insert('/_block/add-js-css'); ?>
<main>
  <h2 class="m0"><?= __('app.add_post'); ?></h2>

  <form class="max-w780" action="<?= url('content.create', ['type' => 'post']); ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?= insert('/_block/form/add-post', ['data' => $data]); ?>
  </form>
</main>
<aside>
  <div class="box bg-beige">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.add_post'); ?>
  </div>
</aside>