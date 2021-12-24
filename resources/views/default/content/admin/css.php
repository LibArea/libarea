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
    'pages'   => []
  ]
); ?>
 
<h4 class="mt5 mb5"><?= Translate::get('Background'); ?>:</h4>
<div class="gap-4 pr10 pl10 justify-between">
 
  <?php // TODO: We integrate with JS to form an array
  $sections = [
    ['bg' => 'bg-white'], ['br' => 'br'],
    ['bg' => 'bg-red-100'], ['bg' => 'bg-red-300'], ['bg' => 'bg-red-500', 'color' => 'white'], ['br' => 'br'],
    ['bg' => 'bg-orange-100'], ['bg' => 'bg-orange-300'], ['br' => 'br'],
    ['bg' => 'bg-yellow-100'], ['bg' => 'bg-yellow-300'], ['bg' => 'bg-yellow-500'], ['br' => 'br'],
    ['bg' => 'bg-green-100'], ['bg' => 'bg-green-200'], ['bg' => 'bg-green-300'], ['bg' => 'bg-green-400'], ['bg' => 'bg-green-500', 'color' => 'white'], ['br' => 'br'],
    ['bg' => 'bg-teal-100'], ['bg' => 'bg-teal-300'], ['br' => 'br'],
    ['bg' => 'bg-blue-100'], ['bg' => 'bg-blue-300'], ['bg' => 'bg-blue-400'], ['bg' => 'bg-blue-500'], ['bg' => 'bg-blue-800', 'color' => 'white'], ['br' => 'br'],
    ['bg' => 'bg-indigo-100'], ['bg' => 'bg-indigo-300'], ['br' => 'br'],
    ['bg' => 'bg-purple-100'], ['bg' => 'bg-purple-300'], ['br' => 'br'],
    ['bg' => 'bg-gray-000'], ['bg' => 'bg-gray-100'], ['bg' => 'bg-gray-200'], ['bg' => 'bg-gray-300'], ['bg' => 'bg-gray-400'], ['bg' => 'bg-gray-700', 'color' => 'white'], ['bg' => 'bg-gray-800', 'color' => 'white'], ['bg' => 'bg-gray-900', 'color' => 'white'], ['br' => 'br'],
  ];

  foreach ($sections as $section) { ?>
    <?php if (!empty($section['br'])) { ?><div class="w-100"><?php } ?>
    <?php if (!empty($section['bg'])) { ?>
      <div class="br-box-gray min-w140 center inline table <?= $section['bg']; ?> m5 p20 mb15">
        <div class="size-15<?php if (!empty($section['color'])) { ?> white<?php } ?>"><?= $section['bg']; ?></div>
      </div>
    <?php } ?>
    <?php if (!empty($section['br'])) { ?></div><?php } ?>
  <?php } ?>
</div>

<div class="white-box mt10 mb15 pt5 pr15 pb15 pl15">
  <h4 class="mt5 mb5"><?= Translate::get('Color'); ?>:</h4>
    <i><?= Translate::get('under development'); ?></i>
</div>
</main>