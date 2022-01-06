<main class="col-span-9 mb-col-12">

  <div class="bg-white flex flex-row items-center justify-between box-shadow-all br-rd5 p15 mb15">
    <p class="m0 mb-none"><?= Translate::get($data['sheet']); ?></p>
    <?= import('/content/user/setting/nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class=" bg-white pt15 pr15 pb5 pl15 box setting avatar">
    <form action="<?= getUrlByName('setting.security.edit'); ?>" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <?= import(
        '/_block/form/field-input',
        [
          'uid'  => $uid,
          'data' => [
            [
              'title' => Translate::get('old'),
              'type' => 'text',
              'name' => 'password',
            ],
            [
              'title' => Translate::get('new'),
              'type' => 'password',
              'name' => 'password2',
              'min' => 6,
              'max' => 32,
              'help' => '6 - 32 ' . Translate::get('characters')
            ],
            [
              'title' => Translate::get('repeat'),
              'type' => 'password',
              'name' => 'password3',
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
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('info-security'), 'uid' => $uid]); ?>