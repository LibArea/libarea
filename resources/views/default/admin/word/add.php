<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    getUrlByName('admin.words'),
    Translate::get('stop words'),
    Translate::get('add a stop word')
  ); ?>

  <form action="<?= getUrlByName('admin.word.create'); ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb20 max-w780">
      <label class="block" or="post_title"><?= Translate::get('stop word'); ?></label>
      <input type="text" class="w-100 h30" name="word">
    </div>
    <?= sumbit(Translate::get('add')); ?>
  </form>
</main>