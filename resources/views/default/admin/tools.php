<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => 'tools', 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb(
    '/admin',
    Translate::get('admin'),
    null,
    null,
    Translate::get('tools')
  ); ?>
  <div class="mb20">
    <label class="required block pb5"><?= Translate::get('topics'); ?> / <?= Translate::get('posts'); ?></label>
    <a class="size-14 mt5 button white" href="<?= getUrlByName('admin.count.topic'); ?>">
      <?= Translate::get('update the data'); ?>
    </a>
  </div>
  <div class="mb20">
    <label class="required block pt15 pb5"><?= Translate::get('like'); ?></label>
    <a class="size-14 mt5 button white" href="<?= getUrlByName('admin.count.up'); ?>">
      <?= Translate::get('update the data'); ?>
    </a>
  </div>
  <div class="mb20">
    <label class="required block pt15 pb5"><?= Translate::get('Email'); ?></label>
    <form action="<?= getUrlByName('admin.test.mail'); ?>" method="post">
      <input class="w-100 h30" type="mail" name="mail" value="">
      <div class="size-14 gray-light-2"><?= Translate::get('test-email'); ?>...</div>
      <input type="submit" class="button block mt5 br-rd5 white" name="submit" value="<?= Translate::get('send'); ?>" />
    </form>
  </div>
</main>