<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/auth.login'));
?>

<div class="col-span-2 mb-none"></div>
<main class="col-span-8 mb-col-12 box-white">
  <h1 class="center"><?= Translate::get('authorization'); ?></h1>
  <form class="max-w300" action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= $form->build_form(); ?>

    <fieldset>
      <?= sumbit(Translate::get('sign.in')); ?>
      <?php if (Config::get('general.invite') == false) { ?>
        <a class="ml20 text-sm" href="<?= getUrlByName('register'); ?>"><?= Translate::get('sign.up'); ?></a>
      <?php } ?>
      <a class="ml20 text-sm" href="<?= getUrlByName('recover'); ?>"><?= Translate::get('forgot.password'); ?>?</a>
    </fieldset>
  </form>
  <?php if (Config::get('general.invite') == 1) { ?>
    <?= Translate::get('no-invate-txt'); ?>
  <?php } ?>
  <p><?= Translate::get('login-use-condition'); ?>.</p>
  <p><?= Translate::get('info-login'); ?></p>
  <img class="right" alt="<?= Config::get('meta.name'); ?>" src="<?= Config::get('meta.img_footer_path'); ?>">
</main>