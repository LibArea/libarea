<div class="wrap">
  <main class="white-box pt15 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Notifications')); ?>
    <?php includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>

    <form class="pt10" action="/users/setting/notifications/edit" method="post">
      <?php csrf_field(); ?>

      <b><?= lang('Email Notification'); ?>?</b>
      <?php field('radio', [
        ['title' => lang('When the message came to PM'), 'name' => 'setting_email_pm', 'checked' => !empty($data['setting']['setting_email_pm'])],
        ['title' => lang('When you contacted me via @'), 'name' => 'setting_email_appealed', 'checked' => !empty($data['setting']['setting_email_appealed'])],
      ]); ?>

      <div class="boxline">
        <input type="hidden" name="nickname" id="nickname" value="">
        <button type="submit" class="button"><?= lang('Edit'); ?></button>
      </div>
    </form>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info-notification'); ?>...
    </div>
  </aside>
</div>