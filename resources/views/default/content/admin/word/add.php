<div class="sticky mt5 top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.admin'),
      ); ?>
</div>

<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'pages'   => [
      [
        'id' => 'add',
        'url' => getUrlByName($data['type'] . '.add'),
        'name' => Translate::get('add'),
        'icon' => 'bi bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <form action="<?= getUrlByName('admin.word.create'); ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb20 max-w780">
      <label class="block mb5" or="post_title"><?= Translate::get('word'); ?></label>
      <input type="text" class="w-100 h30" name="word">
    </div>
    <?= sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>