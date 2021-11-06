<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb(
    '/admin',
    Translate::get('admin'),
    null,
    null,
    Translate::get('users')
  ); ?>
  <div class="bg-white flex flex-row items-center justify-between br-box-grey p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'users', 'url' => '/admin/users', 'content' => Translate::get('all'), 'icon' => 'bi bi-record-circle'],
      ['id' => 'users-ban', 'url' => '/admin/users/ban', 'content' => Translate::get('banned'), 'icon' => 'bi bi-x-circle'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <div class="bg-white br-box-grey pt5 pr15 pb5 pl15">
    <?php if ($data['alluser']) {  ?>
      <table>
        <thead>
          <th>N</th>
          <th><?= Translate::get('avatar'); ?></th>
          <th><?= Translate::get('information'); ?></th>
          <th>IP <?= Translate::get('registrations'); ?></th>
          <th><?= Translate::get('last'); ?></th>
          <th>Ban</th>
          <th><?= Translate::get('action'); ?></th>
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
              <sup class="gray">TL:<?= $user['user_trust_level']; ?></sup>
              <?php if ($user['user_invitation_id'] != 0) { ?><sup>+ inv. id<?= $user['user_invitation_id']; ?></sup><?php } ?>
              <?php if ($user['user_whisper']) { ?>
                <span data-id="<?= $user['user_whisper']; ?>" class="tips size-13 gray-light">
                  <i class="bi bi-info-square green"></i>
                </span>
              <?php } ?>
              <div class="size-13">
                <?= $user['user_email']; ?>
                <?php if ($user['user_activated'] == 1) { ?>
                  <div class="gray-light"><?= Translate::get('email activated'); ?></div>
                <?php } else { ?>
                  <div class="red"><?= Translate::get('not activated'); ?> e-mail</div>
                <?php } ?>
              </div>
              <?php if ($user['user_limiting_mode'] == 1) { ?>
                <div class="red"><?= Translate::get('dumb mode'); ?></div>
              <?php } ?>
              <?php if (!empty($user['isBan']['banlist_int_num'])) { ?>
                bans: <?= $user['isBan']['banlist_int_num']; ?>
              <?php } ?>
            </td>
            <td class="size-13 align-right">
              <a class="gray-light ml10" href="<?= getUrlByName('admin.regip', ['ip' => $user['user_reg_ip']]); ?>">
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
                <a class="gray-light ml10" href="<?= getUrlByName('admin.logip', ['ip' => $user['last_visit_logs']['latest_ip']]); ?>">
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
                    <span class="red"><?= Translate::get('unban'); ?></span>
                  </span>
                <?php } else { ?>
                  <span class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                    <?= Translate::get('ban it'); ?>
                  </span>
                <?php } ?>
              <?php } else { ?>
                ---
              <?php } ?>
            </td>
            <td class="center">
              <a title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('admin.user.edit', ['id' => $user['user_id']]); ?>">
                <i class="bi bi-pencil size-15"></i>
              </a>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <?= no_content(Translate::get('no users'), 'bi bi-info-lg'); ?>
    <?php } ?>
    <?= pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('admin.users')); ?>
  </div>
  </div>
</main>