<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>
<div class="box bg-white">
  <label><?= __('build'); ?> CSS</label>
  <form action="./update/css">
    <?= Html::sumbit(__('build')); ?>
  </form>
  <fieldset>
    <label><?= __('topics'); ?> / <?= __('posts'); ?></label>
    <form action="./update/topic">
      <?= Html::sumbit(__('update.data')); ?>
    </form>
  </fieldset>
  <fieldset>
    <label><?= __('like'); ?></label>
    <form action="./update/up">
      <?= Html::sumbit(__('update.data')); ?>
    </form>
  </fieldset>
  <fieldset>
    <label><?= __('trust.level'); ?></label>
    <form action="./update/tl">
      <?= Html::sumbit(__('update.data')); ?>
    </form>
  </fieldset>
  <fieldset class="max-w300">
    <label for="mail"><?= __('Email'); ?></label>
    <form action="<?= getUrlByName('admin.test.mail'); ?>" method="post">
      <input type="mail" name="mail" value="" required>
      <div class="help"><?= __('test.email'); ?>...</div>
  </fieldset>    
      <?= Html::sumbit(__('send')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>