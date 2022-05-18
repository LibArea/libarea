<main class="col-two">
  <div class="box pt0">
    <h2 class="text-xl"><?= __('app.add_' . $data['type']); ?></h2>
    <form class="max-w780" id="addFacet" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?= component('add-facet'); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box">
    <h3 class="uppercase-box"><?= __('app.help'); ?></h3>
    <?= __('help.add_' . $data['type']); ?>
  </div>
</aside>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('content.create', ['type' => $data['type']]),
    'redirect'  => $data['type'] == 'category' ? url('web') : '/' . $data['type'] . 's',
    'success'   => __('msg.successfully'),
    'id'        => 'form#addFacet'
  ]
); ?>