<?php
$form = new Forms();
$form->html_form(UserData::getUserTl(), config('form/auth.register'));
?>
<main class="max-w780 mr-auto box">
  <h1 class="center"><?= __('app.' . $data['sheet']); ?></h1>
  <form class="max-w300" action="<?= url('register.add'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= $form->build_form(); ?>

    <?= Tpl::insert('/_block/captcha'); ?>

    <fieldset>
      <?= Html::sumbit(__('app.registration')); ?>
      <a class="ml15 text-sm" href="<?= url('login'); ?>"><?= __('app.sign_in'); ?></a>
    </fieldset>
  </form>
  <p><?= __('app.agree_rules'); ?>.</p>
  <p><?= __('app.security_info'); ?></p>
</main>