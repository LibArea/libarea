<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    getUrlByName('user', ['login' => $uid['user_login']]),
    Translate::get('profile'),
    Translate::get('notifications')
  ); ?>

  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= Translate::get($data['sheet']); ?></p>
    <?= includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>
  <div class="bg-white border-box-1 pt15 pr15 pb5 pl15 box">
    <form action="/users/setting/notifications/edit" method="post">
      <?php csrf_field(); ?>
      <b><?= Translate::get('e-mail notification'); ?>?</b>
      <?= includeTemplate('/_block/form/field-radio', ['data' => [
        [
          'title' => Translate::get('when the message came to PM'),
          'name' => 'setting_email_pm',
          'checked' => !empty($data['setting']['setting_email_pm'])
        ],
        [
          'title' => Translate::get('when you contacted me via @'),
          'name' => 'setting_email_appealed',
          'checked' => !empty($data['setting']['setting_email_appealed'])
        ],
      ]]); ?>

      <div class="mb20">
        <input type="hidden" name="nickname" id="nickname" value="">
        <button type="submit" class="button br-rd5 white"><?= Translate::get('edit'); ?></button>
      </div>
    </form>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('info-notification')]); ?>