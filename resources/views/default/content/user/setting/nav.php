<div class="box-flex-white">
  <ul class="nav">
    <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => 1, 'list' => Config::get('navigation/nav.settings')]); ?>
  </ul>
</div>