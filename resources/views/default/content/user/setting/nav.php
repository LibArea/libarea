<div class="box-flex-white">
  <ul class="nav">
    <?= Html::nav(
      'nav',
      $data['sheet'],
      1,
      $pages = Config::get('menu.settings'),
    ); ?>
  </ul>
</div>