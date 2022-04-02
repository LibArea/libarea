<div class="box-flex-white bg-violet-50">
  <ul class="nav">
    <?= Tpl::import('/_block/navigation/menu', ['type' => $data['sheet'], 'user' => 1, 'list' => Config::get('menu.settings')]); ?>
  </ul>
</div>