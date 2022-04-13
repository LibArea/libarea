<?php
$form = new Forms();
$form->html_form($user['trust_level'], Config::get('form/user-security'));
?>

<main>

  <?= Tpl::insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form action="<?= getUrlByName('setting.security.edit'); ?>" method="post">
      <?php csrf_field(); ?>

      <?= $form->build_form(); ?>

      <fieldset>
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= Html::sumbit(__('edit')); ?>
      </fieldset>
    </form>
  </div>
</main>
<aside>
  <div class="box text-sm">
    <?= __('security.info'); ?>
  </div>
</aside>