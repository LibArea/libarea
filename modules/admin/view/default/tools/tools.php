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
  <div class="update btn btn-primary" data-type="css"><?= __('build'); ?></div>
  <fieldset>
    <label><?= __('topics'); ?> / <?= __('posts'); ?></label>
    <div class="update btn btn-primary" data-type="topic"><?= __('update.data'); ?></div>
  </fieldset>
  <fieldset>
    <label><?= __('like'); ?></label>
    <div class="update btn btn-primary" data-type="up"><?= __('update.data'); ?></div>
  </fieldset>
  <fieldset>
    <label><?= __('trust.level'); ?></label>
    <div class="update btn btn-primary" data-type="tl"><?= __('update.data'); ?></div>
  </fieldset>
  <fieldset>
    <label><?= __('search'); ?></label>
    <div class="update update-index btn btn-primary" data-type="indexer"><?= __('rebuild.index'); ?></div>
  </fieldset>
  <fieldset class="max-w300">
    <label for="mail"><?= __('Email'); ?></label>
    <form action="<?= url('admin.test.mail'); ?>" method="post">
      <input type="mail" name="mail" value="" required>
      <div class="help"><?= __('test.email'); ?>...</div>
  </fieldset>    
      <?= Html::sumbit(__('send')); ?>
  </form>
</div>
</main>
<script nonce="<?= $_SERVER['nonce']; ?>">
document.querySelectorAll(".update-index")
  .forEach(el => el.addEventListener("click", function (e) {
      Notiflix.Loading.standard('<?= __('end.window.close'); ?>...');
}));       
</script>

<?= includeTemplate('/view/default/footer'); ?>