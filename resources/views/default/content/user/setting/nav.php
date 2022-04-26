<div class="box-flex">
  <ul class="nav">
    <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'list' => config('navigation/nav.settings')]); ?>
  </ul>
</div>