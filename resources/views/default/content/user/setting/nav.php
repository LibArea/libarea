<div class="box-flex-white">
  <ul class="nav">
    <?= Tpl::import('/_block/navigation/menu', ['type' => $data['sheet'], 'user' => 1, 'list' => Config::get('menu.settings')]); ?>
  </ul>
</div>