<div class="box bg-lightgray text-sm">
  <h4 class="uppercase-box"><?= __('app.authorization'); ?></h4>
  <form class="max-w300" action="<?= url('enterLogin'); ?>" method="post">
    <?php csrf_field(); ?>
    <?= component('login'); ?>
    <fieldset class="gray-600 center">
      <?= __('app.agree_rules'); ?>
      <a href="<?= url('recover'); ?>"><?= __('app.forgot_password'); ?>?</a>
    </fieldset>
  </form>
</div>