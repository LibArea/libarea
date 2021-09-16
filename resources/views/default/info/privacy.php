<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), getUrlByName('info'), lang('Info'), lang('Privacy Policy')); ?>
    <?= $data['content']; ?>
  </main>
  <aside>
    <?= returnBlock('/info-page-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
  </aside>
</div>