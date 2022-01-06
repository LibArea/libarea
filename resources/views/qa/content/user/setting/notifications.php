<main class="col-span-9 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between box-shadow-all br-rd5 p15 mb15">
    <p class="m0 mb-none"><?= Translate::get($data['sheet']); ?></p>
    <?= import('/content/user/setting/nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>
  <div class="bg-white pt15 pr15 pb5 pl15 box">
    <form action="<?= getUrlByName('setting.notif.edit'); ?>" method="post">
      <?php csrf_field(); ?>
      <b class="mb15 block"><?= Translate::get('e-mail notification'); ?>?</b>
      <?= import(
        '/_block/form/radio',
        [
          'uid'  => $uid,
          'data' => [
            [
              'title'   => Translate::get('message to PM'),
              'name'    => 'setting_email_pm',
              'checked' => $data['setting']['setting_email_pm'] ?? 0,
            ],
            [
              'title'   => Translate::get('contacted via @'),
              'name'    => 'setting_email_appealed',
              'checked' => $data['setting']['setting_email_appealed'] ?? 0,
            ],
          ]
        ]
      ); ?>

      <div class="mb20">
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= sumbit(Translate::get('edit')); ?>
      </div>
    </form>
  </div>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('info-notification'), 'uid' => $uid]); ?>