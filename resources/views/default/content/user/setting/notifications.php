<?php
$form = new Forms();
$form->adding(['name' => 'setting_email_pm', 'type' => 'selected', 'var' => $data['setting']['setting_email_pm']]);
$form->adding(['name' => 'setting_email_appealed', 'type' => 'selected', 'var' => $data['setting']['setting_email_appealed']]);
$form->html_form($user['trust_level'], Config::get('form/user-notifications'));
?>
<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>
  </nav>
</div>

<main class="col-span-7 mb-col-12">
  <?= Tpl::import('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box-white">
    <form action="<?= getUrlByName('setting.notif.edit'); ?>" method="post">
      <?php csrf_field(); ?>
      <?= $form->build_form(); ?>
      <fieldset>
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= sumbit(Translate::get('edit')); ?>
      </fieldset>
    </form>
  </div>
</main>
<aside class="col-span-3 mb-none">
  <div class="box-white text-sm">
    <?=  Translate::get('info-notification'); ?>
  </div>  
</aside>