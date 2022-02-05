<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>  
  </nav>
</div>

<main class="col-span-7 mb-col-12">

  <div class="box-flex-white">
    <p class="m0 mb-none"><?= Translate::get($data['sheet']); ?></p>
    <?= Tpl::import('/content/user/setting/nav', ['data' => $data]); ?>
  </div>

  <div class="box-white setting avatar">
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

      <p>
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= sumbit(Translate::get('edit')); ?>
      </p>
    </form>
  </div>
</main>
<?= Tpl::import('/_block/sidebar/lang', ['lang' => Translate::get('info-security')]); ?>