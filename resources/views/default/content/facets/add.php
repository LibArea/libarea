<main>
  <h2 class="text-xl"><?= __('app.add_' . $data['type']); ?></h2>
  <form class="max-w780" action="<?= url('content.create', ['type' => $data['type']]); ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?= component('add-facet'); ?>
  </form>
</main>

<aside>
  <div class="box bg-beige">
    <h3 class="uppercase-box"><?= __('app.help'); ?></h3>
    <?= __('help.add_' . $data['type']); ?>
  </div>
</aside>