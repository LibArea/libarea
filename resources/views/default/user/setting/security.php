<div class="wrap">
  <main class="white-box pt15 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Change password')); ?>
    <?php includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>

    <form class="pt10" action="/users/setting/security/edit" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <?php field('input', [
        ['title' => lang('Old'), 'type' => 'text', 'name' => 'password', 'value' => ''],
        ['title' => lang('New'), 'type' => 'password', 'name' => 'password2', 'value' => '', 'min' => 6, 'max' => 32, 'help' => '6 - 250 ' . lang('characters')],
        ['title' => lang('Repeat'), 'type' => 'password', 'name' => 'password3', 'value' => ''],
      ]); ?>

      <div class="boxline">
        <input type="hidden" name="nickname" id="nickname" value="">
        <button type="submit" class="button"><?= lang('Edit'); ?></button>
      </div>
    </form>
  </main>
  <?= aside('lang', ['lang' => lang('info-security')]); ?>
</div>