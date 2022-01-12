<?= import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'uid'   => $uid,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => getUrlByName('admin.' . $data['type']),
        'name' => Translate::get('all'),
        'icon' => 'bi bi-record-circle'
      ], [
        'id' => $data['type'] . '.ban',
        'url' => getUrlByName('admin.' . $data['type'] . '.ban'),
        'name' => Translate::get('deleted'),
        'icon' => 'bi bi-x-circle'
      ]
    ]
  ]
); ?>

<div class="bg-white br-box-gray pt5 pr15 pb5 pl15">
  <?php if ($data['alluser']) {  ?>
    <table>
      <thead>
        <th>id</th>
        <th><?= Translate::get('avatar'); ?></th>
        <th><?= Translate::get('information'); ?></th>
        <th>IP <?= Translate::get('registrations'); ?></th>
        <th><?= Translate::get('last'); ?></th>
        <th>Ban</th>
        <th><?= Translate::get('edit'); ?></th>
      </thead>
      <?php foreach ($data['alluser'] as $user) {  ?>
        <tr>
          <td class="center">
            <?= $user['user_id']; ?>
          </td>
          <td class="center">
            <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'w60 h60'); ?>
          </td>
          <td>
            <a href="<?= getUrlByName('profile', ['login' => $user['user_login']]); ?>"><?= $user['user_login']; ?></a>
            <?php if ($user['user_name']) { ?>
              (<?= $user['user_name']; ?>)
            <?php } ?>
            <sup class="gray">TL:<?= $user['user_trust_level']; ?></sup>
            <?php if ($user['user_invitation_id'] != 0) { ?><sup>+ inv. id<?= $user['user_invitation_id']; ?></sup><?php } ?>
            <?php if ($user['user_whisper']) { ?>
              <span title="<?= $user['user_whisper']; ?>" class="tips text-sm gray-600">
                <i class="bi bi-info-square green-600"></i>
              </span>
            <?php } ?>
            <div class="text-sm">
              <?= $user['user_email']; ?>
              <?php if ($user['user_activated'] == 1) { ?>
                <div class="gray-600"><?= Translate::get('email activated'); ?></div>
              <?php } else { ?>
                <div class="red-500"><?= Translate::get('not activated'); ?> e-mail</div>
              <?php } ?>
            </div>
            <?php if ($user['user_limiting_mode'] == 1) { ?>
              <div class="red-500"><?= Translate::get('dumb mode'); ?></div>
            <?php } ?>
            <?php if (!empty($user['banlist_int_num'])) { ?>
              <div class="red-500">bans: <?= $user['banlist_int_num']; ?></div>
            <?php } ?>
          </td>
          <td class="text-sm align-right">
            <a class="gray-600 ml10" href="<?= getUrlByName('admin.regip', ['ip' => $user['user_reg_ip']]); ?>">
              <?= $user['user_reg_ip']; ?>
            </a>
            <?php if ($user['duplicat_ip_reg'] > 1) { ?>
              <sup class="red-500">(<?= $user['duplicat_ip_reg']; ?>)</sup>
            <?php } ?>
            <br>
            <?= $user['created_at']; ?>
          </td>
          <td class="text-sm align-right">
            <?php if (!empty($user['last_visit_logs']['latest_ip'])) { ?>
              <a class="gray-600 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $user['last_visit_logs']['latest_ip']]); ?>">
                <?= $user['last_visit_logs']['latest_ip']; ?>
              </a>
              <br>
            <?php } ?>
            <?php if (!empty($user['last_visit_logs']['latest_date'])) { ?>
              <?= $user['last_visit_logs']['latest_date']; ?>
            <?php } ?>
          </td>
          <td class="center">
            <?php if ($user['user_trust_level'] != UserData::REGISTERED_ADMIN) { ?>
              <?php if ($user['user_ban_list']) { ?>
                <span class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                  <span class="red-500"><?= Translate::get('unban'); ?></span>
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
              <i class="bi bi-pencil"></i>
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