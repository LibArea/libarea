<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>
    </div>
    <?php includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  </main>
  <aside>
    <?php includeTemplate('/_block/user-menu', ['uid' => $uid]); ?>
  </aside>
</div>