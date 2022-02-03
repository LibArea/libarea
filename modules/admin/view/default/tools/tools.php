<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="box-white">
  <label><?= Translate::get('build'); ?> CSS</label>
  <form action="<?= getUrlByName('admin.build.css'); ?>">
    <?= sumbit(Translate::get('build')); ?>
  </form>
  <fieldset>
    <label><?= Translate::get('topics'); ?> / <?= Translate::get('posts'); ?></label>
    <form action="<?= getUrlByName('admin.count.topic'); ?>">
      <?= sumbit(Translate::get('update.data')); ?>
    </form>
  </fieldset>
  <fieldset>
    <label><?= Translate::get('like'); ?></label>
    <form action="<?= getUrlByName('admin.count.up'); ?>">
      <?= sumbit(Translate::get('update.data')); ?>
    </form>
  </fieldset>
  <fieldset>
    <label><?= Translate::get('trust level'); ?></label>
    <form action="<?= getUrlByName('admin.users.tl'); ?>">
      <?= sumbit(Translate::get('update.data')); ?>
    </form>
  </fieldset>
  <fieldset>
    <label><?= Translate::get('Email'); ?></label>
    <form action="<?= getUrlByName('admin.test.mail'); ?>" method="post">
      <input class="w-100 h30" type="mail" name="mail" value="">
      <div class="text-sm mt5 mb5 gray-400"><?= Translate::get('test.email'); ?>...</div>
      <?= sumbit(Translate::get('send')); ?>
    </form>
  </fieldset>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>