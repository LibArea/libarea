<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <h1><?= lang('Users'); ?></h1>
      <div class="all-users">
        <?php foreach ($data['users'] as $ind => $user) { ?>
          <div class="column">
            <div class="user_card">
              <div>
                <a href="/u/<?= $user['user_login']; ?>">
                  <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'gr small'); ?>
                </a>
              </div>
              <div class="box-footer size-13">
                <a href="/u/<?= $user['user_login']; ?>"><?= $user['user_login']; ?></a>
                <br>
                <?php if ($user['user_name']) { ?>
                  <?= $user['user_name']; ?>
                <?php } ?>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/users'); ?>
  </main>
  <?= aside('lang', ['lang' => lang('info-users')]); ?>
</div>