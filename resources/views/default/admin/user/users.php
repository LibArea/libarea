<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('users')); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'users', 'url' => '/admin/users', 'content' => lang('all'), 'icon' => 'bi bi-record-circle'],
      ['id' => 'users-ban', 'url' => '/admin/users/ban', 'content' => lang('banned'), 'icon' => 'bi bi-x-circle'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <div class="bg-white border-box-1 pt5 pr15 pb5 pl15">
    <?php if ($data['alluser']) {  ?>
      <table>
        <thead>
          <th>N</th>
          <th><?= lang('avatar'); ?></th>
          <th><?= lang('information'); ?></th>
          <th>IP <?= lang('registrations'); ?></th>
          <th><?= lang('last'); ?></th>
          <th>Ban</th>
          <th><?= lang('action'); ?></th>
        </thead>
        <?php foreach ($data['alluser'] as $user) {  ?>
          <tr>
            <td class="center">
              <?= $user['user_id']; ?>
            </td>
            <td class="center">
              <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'w64'); ?>
            </td>
            <td>
              <a href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>"><?= $user['user_login']; ?></a>
              <?php if ($user['user_name']) { ?>
                (<?= $user['user_name']; ?>)
              <?php } ?>
              <sup class="red">TL:<?= $user['user_trust_level']; ?></sup>
              <?php if ($user['user_invitation_id'] != 0) { ?><sup>+ inv. id<?= $user['user_invitation_id']; ?></sup><?php } ?>
              <?php if ($user['user_whisper']) { ?>
                <span data-id="<?= $user['user_whisper']; ?>" class="tips size-13 gray-light">
                  <i class="bi bi-info-square green"></i>
                </span>
              <?php } ?>
              <div class="size-13">
                <?= $user['user_email']; ?>
                <?php if ($user['user_activated'] == 1) { ?>
                  <div class="gray-light"><?= lang('email activated'); ?></div>
                <?php } else { ?>
                  <div class="red"><?= lang('not activated'); ?> e-mail</div>
                <?php } ?>
              </div>
              <?php if ($user['user_limiting_mode'] == 1) { ?>
                <div class="red"><?= lang('dumb mode'); ?></div>
              <?php } ?>
              <?php if (!empty($user['isBan']['banlist_int_num'])) { ?>
                bans: <?= $user['isBan']['banlist_int_num']; ?>
              <?php } ?>
            </td>
            <td class="size-13 align-right">
              <a class="gray-light ml10" href="/admin/regip/<?= $user['user_reg_ip']; ?>">
                <?= $user['user_reg_ip']; ?>
              </a>
              <?php if ($user['duplicat_ip_reg'] > 1) { ?>
                <sup class="red">(<?= $user['duplicat_ip_reg']; ?>)</sup>
              <?php } ?>
              <br>
              <?= $user['created_at']; ?>
            </td>
            <td class="size-13 align-right">
              <?php if (!empty($user['last_visit_logs']['latest_ip'])) { ?>
                <a class="gray-light ml10" href="/admin/logip/<?= $user['last_visit_logs']['latest_ip']; ?>">
                  <?= $user['last_visit_logs']['latest_ip']; ?>
                </a>
                <br>
              <?php } ?>
              <?php if (!empty($user['last_visit_logs']['latest_date'])) { ?>
                <?= $user['last_visit_logs']['latest_date']; ?>
              <?php } ?>
            </td>
            <td class="center">
              <?php if ($user['user_trust_level'] != 5) { ?>
                <?php if ($user['isBan']) { ?>
                  <span class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                    <span class="red"><?= lang('unban'); ?></span>
                  </span>
                <?php } else { ?>
                  <span class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                    <?= lang('ban it'); ?>
                  </span>
                <?php } ?>
              <?php } else { ?>
                ---
              <?php } ?>
            </td>
            <td class="center">
              <a title="<?= lang('edit'); ?>" href="/admin/users/<?= $user['user_id']; ?>/edit">
                <i class="bi bi-pencil size-15"></i>
              </a>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'no users']); ?>
    <?php } ?>
    <?= pagination($data['pNum'], $data['pagesCount'], null, '/admin/users'); ?>
  </div>
  </div>
</main>