<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Change password')); ?>
      <?php includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
    </div>
    <div class="white-box pt15 pr15 pb5 pl15">
      <form action="/users/setting/security/edit" method="post" enctype="multipart/form-data">
        <?php csrf_field(); ?>

        <?php field_input(array(
          array('title' => lang('Old'), 'type' => 'text', 'name' => 'password', 'value' => $data['password']),
          array('title' => lang('New'), 'type' => 'text', 'name' => 'password2', 'value' => $data['password2'], 'min' => 6, 'max' => 32, 'help' => '6 - 250 ' . lang('characters')),
          array('title' => lang('Repeat'), 'type' => 'text', 'name' => 'password3', 'value' => $data['password3']),
        )); ?>

        <div class="boxline">
          <input type="hidden" name="nickname" id="nickname" value="">
          <button type="submit" class="button"><?= lang('Edit'); ?></button>
        </div>
      </form>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info-security'); ?>
    </div>
  </aside>
</div>