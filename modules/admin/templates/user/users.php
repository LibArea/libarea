<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/admin', lang('Admin'), '/admin/users', lang('Users'), $data['meta_title']); ?>

        <ul class="nav-tabs list-none">
          <?php if ($data['sheet'] == 'users') { ?>
            <li class="active">
              <span><?= lang('All'); ?></span>
            </li>
            <li>
              <a href="/admin/users/ban">
                <span><?= lang('Banned'); ?></span>
              </a>
            </li>
          <?php } elseif ($data['sheet'] == 'users-ban') { ?>
            <li>
              <a href="/admin/users">
                <span><?= lang('All'); ?></span>
              </a>
            </li>
            <li class="active">
              <span><?= lang('Banned'); ?></span>
            </li>
          <?php } ?>
        </ul>

        <div class="t-table">
          <div class="t-th">
            <span class="t-td center">N</span>
            <span class="t-td center"><?= lang('Avatar'); ?></span>
            <span class="t-td"><?= lang('Information'); ?></span>
            <span class="t-td">IP <?= lang('registrations'); ?></span>
            <span class="t-td"><?= lang('Last'); ?></span>
            <span class="t-td center">Ban</span>
            <span class="t-td center"><?= lang('Action'); ?></span>
          </div>
          <?php if ($alluser) {  ?>
            <?php foreach ($alluser as $user) {  ?>

              <div class="t-tr">
                <span class="t-td width-30 center">
                  <?= $user['user_id']; ?>
                </span>
                <span class="t-td width-30 center">
                  <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'ava-64'); ?>
                </span>
                <span class="t-td">
                  <a href="/u/<?= $user['user_login']; ?>"><?= $user['user_login']; ?></a>
                  <?php if ($user['user_name']) { ?>
                    (<?= $user['user_name']; ?>)
                  <?php } ?>
                  <sup class="red">TL:<?= $user['user_trust_level']; ?></sup>
                  <?php if ($user['user_invitation_id'] != 0) { ?><sup>+ inv. id<?= $user['user_invitation_id']; ?></sup><?php } ?>
                  <div class="size-13">
                    <?= $user['user_email']; ?>
                  </div>
                  <?php if ($user['user_limiting_mode'] == 1) { ?>
                    <div class="red"><?= lang('Dumb mode'); ?></div>
                  <?php } ?>

                  <?php if (!empty($user['isBan']['banlist_int_num'])) { ?>
                    bans: <?= $user['isBan']['banlist_int_num']; ?>
                  <?php } ?>
                </span>
                <span class="t-td size-13">
                  <?= $user['user_reg_ip']; ?>
                  <?php if ($user['replayIp'] > 1) { ?>
                    <sup class="red">(<?= $user['replayIp']; ?>)</sup>
                  <?php } ?> <br>
                  <?= $user['created_at']; ?>
                </span>
                <span class="t-td size-13">
                  <?php if (!empty($user['logs']['logs_ip_address'])) { ?>
                    <a href="/admin/logip/<?= $user['logs']['logs_ip_address']; ?>">
                      <?= $user['logs']['logs_ip_address']; ?>
                    </a>
                    <br>
                   <?= $user['logs']['logs_date']; ?>
                  <?php } ?>

                  <?php if ($user['user_activated'] == 1) { ?>
                    <div><?= lang('Email activated'); ?></div>
                  <?php } else { ?>
                    <span class="red"><?= lang('Not activated'); ?> e-mail</span>
                  <?php } ?>
                </span>
                <span class="t-td center">
                  <?php if ($user['user_trust_level'] != 5) { ?>
                    <?php if ($user['isBan']) { ?>
                      <span class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                        <span class="red"><?= lang('Unban'); ?></span>
                      </span>
                    <?php } else { ?>
                      <span class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                        <?= lang('Ban it'); ?>
                      </span>
                    <?php } ?>
                  <?php } else { ?>
                    ---
                  <?php } ?>
                </span>
                <span class="t-td center">
                  <?php if ($user['user_trust_level'] != 5) { ?>
                    <a title="<?= lang('Edit'); ?>" href="/admin/users/<?= $user['user_id']; ?>/edit">
                      <i class="icon-pencil size-15"></i>
                    </a>
                  <?php } else { ?>
                    ---
                  <?php } ?>
                </span>
              </div>
            <?php } ?>

          <?php } else { ?>
            <?= no_content('No users'); ?>
          <?php } ?>
        </div>

        <?= pagination($data['pNum'], $data['pagesCount'], null, '/admin/users'); ?>
      </div>
    </div>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>