<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $user,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12">

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 mb-none"><?= Translate::get($data['sheet']); ?></p>
    <?= Tpl::import('/content/user/setting/nav', ['data' => $data]); ?>
  </div>

  <div class=" bg-white br-box-gray pt15 pr15 pb5 pl15 box setting avatar">
    <form action="<?= getUrlByName('setting.security.edit'); ?>" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <?= Tpl::import(
        '/_block/form/field-input',
        [
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
<?= Tpl::import('/_block/sidebar/lang', ['lang' => Translate::get('info-security')]); ?>