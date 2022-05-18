<div class="box text-sm">
  <h3 class="uppercase-box"><?= __('app.authorization'); ?></h3>
  <form class="max-w300" id="login">
    <?php csrf_field(); ?>
    <?= component('login'); ?>
    <fieldset class="gray-600 center">
      <?= __('app.agree_rules'); ?>
      <a href="<?= url('recover'); ?>"><?= __('app.forgot_password'); ?>?</a>
    </fieldset>
  </form>
</div>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('enterLogin'),
    'redirect'  => '/',
    'success'   => __('msg.successfully'),
    'id'        => 'form#login'
  ]
); ?>