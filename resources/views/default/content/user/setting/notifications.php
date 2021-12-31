<div class="col-span-2 justify-between no-mob">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= Translate::get($data['sheet']); ?></p>
    <?= import('/content/user/setting/nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>
  <div class="bg-white br-box-gray pt15 pr15 pb5 pl15 box">
    <form action="<?= getUrlByName('setting.notif.edit'); ?>" method="post">
      <?php csrf_field(); ?>
      <b class="mb15 block"><?= Translate::get('e-mail notification'); ?>?</b>
      <?= import(
        '/_block/form/radio',
        [
          'uid'  => $uid,
          'data' => [
            [
              'title'   => Translate::get('when the message came to PM'),
              'name'    => 'setting_email_pm',
              'checked' => $data['setting']['setting_email_pm'] ?? 0,
            ],
            [
              'title'   => Translate::get('when you contacted me via @'),
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