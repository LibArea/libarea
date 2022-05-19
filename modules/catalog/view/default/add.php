<?= includeTemplate('/view/default/header', ['data' => $data, 'meta' => $meta]); ?>

<div id="contentWrapper">
  <main>
    <?= insert('/_block/navigation/breadcrumbs', [
      'list' => [
        [
          'name' => __('web.catalog'),
          'link' => url('web')
        ], [
          'name' => __('web.edit_website'),
          'link' => 'red'
        ],
      ]
    ]); ?>

    <form id="addWebsite" class="max-w640">
      <?= csrf_field() ?>
      <?= includeTemplate('/view/default/_block/category', ['data' => ['topic' => false], 'action' => 'add']); ?>   
      <?= includeTemplate('/view/default/_block/add-website'); ?>
      <?= Html::sumbit(__('web.add')); ?>
    </form>
  </main>
  <aside>
    <div class="box box-shadow-all text-sm">
      <h3 class="uppercase-box"><?= __('web.help'); ?></h3>
      <?= __('web.data_help'); ?>
      <div>
  </aside>
</div>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('content.create', ['type' => 'item']),
    'redirect'  => UserData::checkAdmin() ? url('web') : url('web.user.sites'),
    'success'   => __('msg.successfully'),
    'id'        => 'form#addWebsite'
  ]
); ?>

<?= includeTemplate('/view/default/footer'); ?>