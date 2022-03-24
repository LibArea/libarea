<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/auth.register'));
?>
<main class="max-w780 mr-auto">
  <h1 class="center"><?= Translate::get($data['sheet']); ?></h1>
  <form class="max-w300" action="<?= getUrlByName('register.add'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= $form->build_form(); ?>

    <?= Tpl::import('/_block/captcha'); ?>

    <fieldset>
      <?= Html::sumbit(Translate::get('registration')); ?>
      <a class="ml15 text-sm" href="<?= getUrlByName('login'); ?>"><?= Translate::get('sign.in'); ?></a>
    </fieldset>
  </form>
  <p><?= Translate::get('login.use.condition'); ?>.</p>
  <p><?= Translate::get('info-security'); ?></p>
</main>