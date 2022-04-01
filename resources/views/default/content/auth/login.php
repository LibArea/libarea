<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/auth.login'));
?>

<main class="max-w780 mr-auto box-white">
  <h1 class="center"><?= Translate::get('authorization'); ?></h1>
  <form class="max-w300" action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= $form->build_form(); ?>

    <fieldset>
      <?= Html::sumbit(Translate::get('sign.in')); ?>
      <?php if (Config::get('general.invite') == false) { ?>
        <a class="ml20 text-sm" href="<?= getUrlByName('register'); ?>"><?= Translate::get('registration'); ?></a>
      <?php } ?>
      <a class="ml20 text-sm" href="<?= getUrlByName('recover'); ?>"><?= Translate::get('forgot.password'); ?>?</a>
    </fieldset>
  </form>
  <?php if (Config::get('general.invite') == 1) { ?>
    <?= Translate::get('invate.text'); ?>
  <?php } ?>
  <p><?= Translate::get('login.use.condition'); ?>.</p>
  <p><?= Translate::get('login.info'); ?></p>
</main>