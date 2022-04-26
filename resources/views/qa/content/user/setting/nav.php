<div class="box-flex bg-violet">
  <ul class="nav">
    <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'list' => config('navigation/nav.settings')]); ?>
  </ul>
</div>