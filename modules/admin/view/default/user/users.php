<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => getUrlByName('admin.' . $data['type']),
        'name' => Translate::get('all'),
        'icon' => 'bi-record-circle'
      ], [
        'id' => $data['type'] . '.ban',
        'url' => getUrlByName('admin.' . $data['type'] . '.ban'),
        'name' => Translate::get('deleted'),
        'icon' => 'bi-x-circle'
      ]
    ]
  ]
); ?>

<div class="box-white">
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
            <?= $user['id']; ?>
          </td>
          <td class="center">
            <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'ava-lg'); ?>
          </td>
          <td>
            <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
            <?php if ($user['name']) { ?>
              (<?= $user['name']; ?>)
            <?php } ?>
            <sup class="gray">TL:<?= $user['trust_level']; ?></sup>
            <?php if ($user['invitation_id'] != 0) { ?><sup>+ inv. id<?= $user['invitation_id']; ?></sup><?php } ?>
            <?php if ($user['whisper']) { ?>
              <span title="<?= $user['whisper']; ?>" class="tips text-sm gray-600">
                <i class="bi-info-square green"></i>
              </span>
            <?php } ?>
            <div class="text-sm">
              <?= $user['email']; ?>
              <?php if ($user['activated'] == 1) { ?>
                <div class="gray-600"><?= Translate::get('email.activated'); ?></div>
              <?php } else { ?>
                <div class="red"><?= Translate::get('not.activated'); ?> e-mail</div>
              <?php } ?>
            </div>
            <?php if ($user['limiting_mode'] == 1) { ?>
              <div class="red"><?= Translate::get('dumb mode'); ?></div>
            <?php } ?>
            <?php if (!empty($user['banlist_int_num'])) { ?>
              <div class="red">bans: <?= $user['banlist_int_num']; ?></div>
            <?php } ?>
          </td>
          <td class="text-sm align-right">
            <a class="gray-600 ml10" href="<?= getUrlByName('admin.regip', ['ip' => $user['reg_ip']]); ?>">
              <?= $user['reg_ip']; ?>
            </a>
            <?php if ($user['duplicat_ip_reg'] > 1) { ?>
              <sup class="red">(<?= $user['duplicat_ip_reg']; ?>)</sup>
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
            <?php if ($user['trust_level'] != UserData::REGISTERED_ADMIN) { ?>
              <?php if ($user['ban_list']) { ?>
                <span class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
                  <span class="red"><?= Translate::get('unban'); ?></span>
                </span>
              <?php } else { ?>
                <span class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
                  <?= Translate::get('ban it'); ?>
                </span>
              <?php } ?>
            <?php } else { ?>
              ---
            <?php } ?>
          </td>
          <td class="center">
            <a title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('admin.user.edit', ['id' => $user['id']]); ?>">
              <i class="bi-pencil"></i>
            </a>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi-info-lg'); ?>
  <?php } ?>
  <?= pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('admin.users')); ?>
</div>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>