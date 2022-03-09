<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/auth.login'));
?>
<div class="box-white text-sm">
  <form action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>
    <?= $form->build_form(); ?>
    <fieldset>
      <?= sumbit(Translate::get('sign.in')); ?>
    </fieldset>
    <fieldset class="gray-600 center">
      <?= Translate::get('login.use.condition'); ?>
      <a href="<?= getUrlByName('recover'); ?>"><?= Translate::get('forgot.password'); ?>?</a>
    </fieldset>
  </form>
</div>