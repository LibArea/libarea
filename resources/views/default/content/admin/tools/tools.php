<div class="sticky mt5 top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.admin'),
      ); ?>
</div>

<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'pages'   => []
  ]
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
    <label class="required block mb5"><?= Translate::get('trust level'); ?></label>
    <form action="<?= getUrlByName('admin.users.tl'); ?>">
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
  
  <div class="mt15 pt15">
    <?= Translate::get('see more'); ?>: <a title="css" class="p5 pr15 pl15 white white-hover bg-red-500" href="<?= getUrlByName('admin.сss'); ?>">CSS</a>
  </div>
</div>
</main>