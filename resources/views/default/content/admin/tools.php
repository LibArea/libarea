<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/admin', ['sheet' => 'tools', 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('tools')
  ); ?>

  <div class="bg-white br-box-gray p15">
    <div class="mb20">
      <label class="required block mb5"><?= Translate::get('build'); ?> CSS</label>
      <form action="<?= getUrlByName('admin.build.css'); ?>">
        <?= sumbit(Translate::get('build')); ?>
      </form>
    </div>
    <div class="mb20">
      <label class="required block mb5"><?= Translate::get('topics'); ?> / <?= Translate::get('posts'); ?></label>
      <form action="<?= getUrlByName('admin.count.topic'); ?>">
        <?= sumbit(Translate::get('update the data')); ?>
      </form>
    </div>
    <div class="mb20">
      <label class="required block mb5"><?= Translate::get('like'); ?></label>
      <form action="<?= getUrlByName('admin.count.up'); ?>">
        <?= sumbit(Translate::get('update the data')); ?>
      </form>
    </div>
    <div class="mb20">
      <label class="required block mb5"><?= Translate::get('Email'); ?></label>
      <form action="<?= getUrlByName('admin.test.mail'); ?>" method="post">
        <input class="w-100 h30" type="mail" name="mail" value="">
        <div class="size-14 mt5 mb5 gray-light-2"><?= Translate::get('test-email'); ?>...</div>
        <?= sumbit(Translate::get('send')); ?>
      </form>
    </div>
  </div>
</main>