<main class="col-span-9 mb-col-12 bg-white br-rd5 br-box-gray p15">
  <h1 class="mt0 mb10 size-24 font-normal"><?= Translate::get('info'); ?></h1>
  <?= $data['content']; ?>
</main>
<?= import('/_block/menu/page-info', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
<?= import('/_block/wide-footer'); ?>