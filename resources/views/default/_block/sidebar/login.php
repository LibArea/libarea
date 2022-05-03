<?php
$form = new Forms();
$form->html_form(UserData::getUserTl(), config('form/auth.login'));
?>
<div class="box text-sm">
  <form action="<?= url('login'); ?>" method="post">
    <?php csrf_field(); ?>
    <?= $form->build_form(); ?>
    <fieldset>
      <?= Html::sumbit(__('app.sign_in')); ?>
    </fieldset>
    <fieldset class="gray-600 center">
      <?= __('app.agree_rules'); ?>
      <a href="<?= url('recover'); ?>"><?= __('app.forgot_password'); ?>?</a>
    </fieldset>
  </form>
</div>