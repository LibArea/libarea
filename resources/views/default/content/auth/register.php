<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/auth.register'));
?>
<main class="max-w780 mr-auto box">
  <h1 class="center"><?= __($data['sheet']); ?></h1>
  <form class="max-w300" action="<?= getUrlByName('register.add'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= $form->build_form(); ?>

    <?= Tpl::insert('/_block/captcha'); ?>

    <fieldset>
      <?= Html::sumbit(__('registration')); ?>
      <a class="ml15 text-sm" href="<?= getUrlByName('login'); ?>"><?= __('sign.in'); ?></a>
    </fieldset>
  </form>
  <p><?= __('login.use.condition'); ?>.</p>
  <p><?= __('security.info'); ?></p>
</main>