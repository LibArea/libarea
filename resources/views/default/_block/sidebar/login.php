<?php
$form = new Forms();
$form->html_form(UserData::getUserTl(), config('form/auth.login'));
?>
<div class="box text-sm">
  <form action="<?= url('login'); ?>" method="post">
    <?php csrf_field(); ?>
    <?= $form->build_form(); ?>
    <fieldset>
      <?= Html::sumbit(__('sign.in')); ?>
    </fieldset>
    <fieldset class="gray-600 center">
      <?= __('login.use.condition'); ?>
      <a href="<?= url('recover'); ?>"><?= __('forgot.password'); ?>?</a>
    </fieldset>
  </form>
</div>