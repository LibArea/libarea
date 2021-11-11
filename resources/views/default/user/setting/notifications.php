<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= Translate::get($data['sheet']); ?></p>
    <?= includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>
  <div class="bg-white br-box-gray pt15 pr15 pb5 pl15 box">
    <form action="<?= getUrlByName('setting.notif.edit'); ?>" method="post">
      <?php csrf_field(); ?>
      <b class="mb15 block"><?= Translate::get('e-mail notification'); ?>?</b>
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
        <?= sumbit(Translate::get('edit')); ?>
      </div>
    </form>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('info-notification')]); ?>