<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), getUrlByName('user', ['login' => Request::get('login')]), lang('Profile'), $data['h1']); ?>
    </div>
    <?= returnBlock('/post', ['data' => $data, 'uid' => $uid]); ?>
  </main>
  <aside>
    <?= returnBlock('/user-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
  </aside>
</div>