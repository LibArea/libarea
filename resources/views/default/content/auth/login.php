<?php
$form = new Forms();
$form->html_form(UserData::getUserTl(), config('form/auth.login'));
?>

<main class="max-w780 mr-auto box">
  <h1 class="center"><?= __('authorization'); ?></h1>
  <form class="max-w300" action="<?= url('login'); ?>" method="post">
    <?php csrf_field(); ?>

    <?= $form->build_form(); ?>

    <fieldset>
      <?= Html::sumbit(__('sign.in')); ?>
      <?php if (config('general.invite') == false) : ?>
        <a class="ml20 text-sm" href="<?= url('register'); ?>"><?= __('registration'); ?></a>
      <?php endif; ?>
      <a class="ml20 text-sm" href="<?= url('recover'); ?>"><?= __('forgot.password'); ?>?</a>
    </fieldset>
  </form>
  <?php if (config('general.invite') == 1) : ?>
    <?= __('invate.text'); ?>
  <?php endif; ?>
  <p><?= __('login.use.condition'); ?>.</p>
  <p><?= __('login.info'); ?></p>
</main>