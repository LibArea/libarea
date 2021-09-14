<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), '/info', lang('Info'), lang('Info')); ?>
    <?= $data['content']; ?>
  </main>
  <aside>
    <?= returnBlock('/info-page-menu', ['uid' => $uid]); ?>
  </aside>
</div>