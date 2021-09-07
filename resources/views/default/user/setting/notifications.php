<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Notifications')); ?>
      <?php includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
    </div>
    <div class="white-box pt15 pr15 pb5 pl15">
      <form action="/users/setting/notifications/edit" method="post">
        <?php csrf_field(); ?>
        <b><?= lang('Email Notification'); ?>?</b>

        <?php field_radio(array(
          array('title' => lang('When the message came to PM'), 'name' => 'setting_email_pm', 'checked' => !empty($data['setting']['setting_email_pm'])),
          array('title' => lang('When you contacted me via @'), 'name' => 'setting_email_appealed', 'checked' => !empty($data['setting']['setting_email_appealed'])),
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
      <?= lang('info-notification'); ?>...
    </div>
  </aside>
</div>