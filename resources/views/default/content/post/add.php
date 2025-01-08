<?= insert('/_block/add-js-css'); ?>
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