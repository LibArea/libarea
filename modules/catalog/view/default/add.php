<?php
echo includeTemplate('/view/default/header', ['data' => $data, 'meta' => $meta]);
$form = new Forms();
$form->html_form(UserData::getUserTl(), config('form/catalog.site'));
?>

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

    <form id="addUrl" class="max-w640">
      <?= csrf_field() ?>

      <?= includeTemplate('/view/default/_block/category', ['data' => ['topic' => false], 'action' => 'add']); ?>

      <?= $form->build_form(); ?>

      <?= $form->sumbit(__('web.add')); ?>
    </form>
  </main>
  <aside>
    <div class="box box-shadow-all text-sm">
      <h3 class="uppercase-box"><?= __('web.help'); ?></h3>
      <?= __('web.data_help'); ?>
      <div>
  </aside>
</div>

<?php $url = UserData::checkAdmin() ? 'web' : 'web.user.sites'; ?>
<?= includeTemplate('/view/default/_block/ajax', ['url' => 'web.create', 'redirect' => $url, 'id' => 'form#addUrl']); ?>

<?= includeTemplate('/view/default/footer'); ?>