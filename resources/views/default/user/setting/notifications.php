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
        <div class="boxline">
          <label class="form-label" for="post_content">
            <?= lang('When the message came to PM'); ?>
          </label>
          <input type="radio" name="setting_email_pm" <?php if (!empty($data['setting']['setting_email_pm']) == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
          <input type="radio" name="setting_email_pm" <?php if (!empty($data['setting']['setting_email_pm']) == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
        </div>
        <div class="boxline">
          <label class="form-label" for="post_content">
            <?= lang('When you contacted me via @'); ?>
          </label>
          <input type="radio" name="setting_email_appealed" <?php if (!empty($data['setting']['setting_email_appealed']) == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
          <input type="radio" name="setting_email_appealed" <?php if (!empty($data['setting']['setting_email_appealed']) == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
        </div>
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