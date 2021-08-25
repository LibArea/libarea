<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <h1><?= $data['h1']; ?></h1>
        <div class="all-users">
          <?php foreach ($users as $ind => $user) { ?>
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
                  <?php } else { ?>

                  <?php } ?>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/users'); ?>
  </main>
  <aside>
    <?php if ($uid['user_id'] == 0) { ?>
      <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
    <?php } else { ?>
      <div class="white-box">
        <div class="p15">
          <?= lang('info_users'); ?>
        </div>
      </div>
    <?php } ?>
  </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>