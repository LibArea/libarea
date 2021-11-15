<main class="col-span-9 mb-col-12 bg-white br-rd5 br-box-gray pt5 pr15 pb5 pl15">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    null,
    null,
    Translate::get('info')
  ); ?>

  <h1 class="mt0 mb10 size-24 font-normal"><?= Translate::get('info'); ?></h1>
  <?= $data['content']; ?>
</main>
<?= includeTemplate('/_block/menu/menu-page-info', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
<?= includeTemplate('/_block/wide-footer'); ?>