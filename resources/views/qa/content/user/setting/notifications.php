<?php
$form = new Forms();
$form->adding(['name' => 'setting_email_pm', 'type' => 'selected', 'var' => $data['setting']['setting_email_pm']]);
$form->adding(['name' => 'setting_email_appealed', 'type' => 'selected', 'var' => $data['setting']['setting_email_appealed']]);
$form->html_form($user['trust_level'], Config::get('form/user-notifications'));
?>
<main class="col-two">
  <?= Tpl::insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="bg-white box">
    <form action="<?= getUrlByName('setting.notif.edit'); ?>" method="post">
      <?php csrf_field(); ?>
      <?= $form->build_form(); ?>
      <fieldset>
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= Html::sumbit(Translate::get('edit')); ?>
      </fieldset>
    </form>
  </div>
</main>
<aside>
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('notification.info'); ?>
  </div>
</aside>