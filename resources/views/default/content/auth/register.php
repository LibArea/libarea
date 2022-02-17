<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/auth.register'));
?>
<div class="col-span-2 mb-none"></div>
<main class="col-span-8 mb-col-12 box-white">
  <h1 class="center"><?= Translate::get($data['sheet']); ?></h1>
  <form class="max-w300" action="<?= getUrlByName('register.add'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= $form->build_form(); ?>

    <?= Tpl::import('/_block/captcha'); ?>

    <fieldset>
      <?= sumbit(Translate::get('sign.up')); ?>
      <a class="ml15 text-sm" href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign.in'); ?></a>
    </fieldset>
  </form>
  <p><?= Translate::get('login-use-condition'); ?>.</p>
  <p><?= Translate::get('info-security'); ?></p>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_path'); ?>">
</main>