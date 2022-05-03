<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>
<div class="box bg-white">
  <label><?= __('admin.build'); ?> CSS</label>
  <div class="update btn btn-primary" data-type="css"><?= __('admin.build'); ?></div>
  <fieldset>
    <label><?= __('admin.topics'); ?> / <?= __('admin.posts'); ?></label>
    <div class="update btn btn-primary" data-type="topic"><?= __('admin.update'); ?></div>
  </fieldset>
  <fieldset>
    <label><?= __('admin.like'); ?></label>
    <div class="update btn btn-primary" data-type="up"><?= __('admin.update'); ?></div>
  </fieldset>
  <fieldset>
    <label><?= __('admin.trust_level'); ?></label>
    <div class="update btn btn-primary" data-type="tl"><?= __('admin.update'); ?></div>
  </fieldset>
  <fieldset>
    <label><?= __('admin.search'); ?></label>
    <div class="update update-index btn btn-primary" data-type="indexer"><?= __('admin.rebuild'); ?></div>
  </fieldset>
  <fieldset class="max-w300">
    <label for="mail"><?= __('admin.email'); ?></label>
    <form action="<?= url('admin.test.mail'); ?>" method="post">
      <input type="mail" name="mail" value="" required>
      <div class="help"><?= __('admin.test_email'); ?>...</div>
  </fieldset>    
      <?= Html::sumbit(__('admin.send')); ?>
  </form>
</div>
</main>
<script nonce="<?= $_SERVER['nonce']; ?>">
document.querySelectorAll(".update-index")
  .forEach(el => el.addEventListener("click", function (e) {
      Notiflix.Loading.standard('<?= __('admin.window_close'); ?>...');
}));       
</script>

<?= includeTemplate('/view/default/footer'); ?>