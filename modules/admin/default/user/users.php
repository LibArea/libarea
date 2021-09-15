<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), '/admin/users', lang('Users'), lang('Users')); ?>
      <?php
      $pages = array(
        array('id' => 'users', 'url' => '/admin/users', 'content' => lang('All')),
        array('id' => 'users-ban', 'url' => '/admin/users/ban', 'content' => lang('Banned')),
      );
      echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
      ?>
      <?php if ($data['alluser']) {  ?>
        <table>
          <thead>
            <th>N</th>
            <th><?= lang('Avatar'); ?></th>
            <th><?= lang('Information'); ?></th>
            <th>IP <?= lang('registrations'); ?></th>
            <th><?= lang('Last'); ?></th>
            <th>Ban</th>
            <th><?= lang('Action'); ?></th>
          </thead>
          <?php foreach ($data['alluser'] as $user) {  ?>
            <tr>
              <td class="center">
                <?= $user['user_id']; ?>
              </td>
              <td class="center">
                <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'ava-64'); ?>
              </td>
              <td>
                <a href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>"><?= $user['user_login']; ?></a>
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
              </td>
              <td class="size-13 align-right">
                <a class="gray-light ml10" href="/admin/logip/<?= $user['user_reg_ip']; ?>">
                  <?= $user['user_reg_ip']; ?>
                </a>
                <?php if ($user['replayIp'] > 1) { ?>
                  <sup class="red">(<?= $user['replayIp']; ?>)</sup>
                <?php } ?>
                <br>
                <?= $user['created_at']; ?>
              </td>
              <td class="size-13 align-right">
                <?php if (!empty($user['logs']['logs_ip_address'])) { ?>
                  <a class="gray-light ml10" href="/admin/logip/<?= $user['logs']['logs_ip_address']; ?>">
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
              </td>
              <td class="center">
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
              </td>
              <td class="center">
                <?php if ($user['user_trust_level'] != 5) { ?>
                  <a title="<?= lang('Edit'); ?>" href="/admin/users/<?= $user['user_id']; ?>/edit">
                    <i class="icon-pencil size-15"></i>
                  </a>
                <?php } else { ?>
                  ---
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </table>
      <?php } else { ?>
        <?= returnBlock('no-content', ['lang' => 'No users']); ?>
      <?php } ?>
      <?= pagination($data['pNum'], $data['pagesCount'], null, '/admin/users'); ?>
    </div>
</div>
</main>
</div>