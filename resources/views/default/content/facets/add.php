<main>
  <div class="box">
    <h2 class="text-xl"><?= __('app.add_' . $data['type']); ?></h2>
    <form class="max-w-md" action="<?= url('add.facet', ['type' => $data['type']], method: 'post'); ?>" method="post" enctype="multipart/form-data">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/add-facet'); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.add_' . $data['type']); ?>
  </div>
</aside>