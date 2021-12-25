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
 
<div class="bg-white p15 br-box-gray justify-between">
  <h4 class="mt5 mb5"><?= Translate::get('color'); ?>:</h4>
  <div class="mb30 hidden">
  <?php $i=0; foreach ($data['bg'] as $bg) { ?>
    <?php if($i % 10 == 0) echo "</div><div class=\"mb30 hidden\">"; ?> 
    <div class="br-box-gray relative br-rd3 w160 h80 center inline <?= $bg; ?> pb0 mb15">
      <div class="mt15 w-100 bottom0 pt5 pb5 absolute bg-stone-500 size-14 white">
         <?= $bg; ?> 
      </div>
    </div>
  <?php  $i += 1; } ?>
  </div>

  <div class="mb15">
    Мы можем добавлять / убирать <i class="bg-amber-100">.bg-</i> чтобы получить: 
    <i class="bg-amber-100">.bg-res-400</i> / <i class="bg-amber-100">.res-400</i>.
  </div>

</div>
</main>