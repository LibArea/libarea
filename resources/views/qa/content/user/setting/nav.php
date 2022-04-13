<div class="box-flex bg-violet">
  <ul class="nav">
    <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => 1, 'list' => Config::get('navigation/nav.settings')]); ?>
  </ul>
</div>