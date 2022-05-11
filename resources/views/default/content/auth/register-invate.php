<?php
$form = new Forms();
$form->adding(['name' => 'email', 'type' => 'value', 'var' => $data['invate']['invitation_email']]);
$form->html_form(UserData::getUserTl(), config('form/auth.register'));
?>
<main class="box w-100">
  <div class="pl20">
    <h1><?= __('app.reg_invite'); ?></h1>
    <form class="max-w300" action="<?= url('register'); ?>/add" method="post">
      <?php csrf_field(); ?>

      <?= $form->build_form(); ?>

      <fieldset>
        <input type="hidden" name="invitation_code" value="<?= $data['invate']['invitation_code']; ?>">
        <input type="hidden" name="invitation_id" value="<?= $data['invate']['uid']; ?>">
        <?= Html::sumbit(__('app.registration')); ?>
      </fieldset>
    </form>
  </div>
</main>