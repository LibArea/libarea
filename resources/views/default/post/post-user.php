<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>
    </div>
    <?= returnBlock('/post', ['data' => $data, 'uid' => $uid]); ?>
  </main>
  <aside>
    <?= returnBlock('/user-menu', ['uid' => $uid]); ?>
  </aside>
</div>