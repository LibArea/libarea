<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => getUrlByName('admin.' . $data['type']),
        'name' => __('all'),
        'icon' => 'bi-record-circle'
      ], [
        'id' => $data['type'] . '.ban',
        'url' => getUrlByName('admin.' . $data['type'] . '.ban'),
        'name' => __('deleted'),
        'icon' => 'bi-x-circle'
      ]
    ]
  ]
); ?>

<div class="box">
  <?php if ($data['alluser']) :  ?>
    <table>
      <thead>
        <th>id</th>
        <th><?= __('avatar'); ?></th>
        <th><?= __('information'); ?></th>
        <th>IP <?= __('registrations'); ?></th>
        <th><?= __('last'); ?></th>
        <th>Ban</th>
        <th><?= __('edit'); ?></th>
      </thead>
      <?php foreach ($data['alluser'] as $user) :  ?>
        <tr>
          <td class="center">
            <?= $user['id']; ?>
          </td>
          <td class="center">
            <?= Html::image($user['avatar'], $user['login'], 'ava-lg', 'avatar', 'max'); ?>
          </td>
          <td>
            <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
            <?php if ($user['name']) : ?>
              (<?= $user['name']; ?>)
            <?php endif; ?>
            <sup class="gray">TL:<?= $user['trust_level']; ?></sup>
            <?php if ($user['invitation_id'] != 0) : ?><sup>+ inv. id<?= $user['invitation_id']; ?></sup><?php endif; ?>
            <?php if ($user['whisper']) : ?>
              <span title="<?= $user['whisper']; ?>" class="tips text-sm gray-600">
                <i class="bi-info-square green"></i>
              </span>
            <?php endif; ?>
            <div class="text-sm">
              <?= $user['email']; ?>
              <?php if ($user['activated'] == 1) : ?>
                <div class="gray-600"><?= __('email.activated'); ?></div>
              <?php else : ?>
                <div class="red"><?= __('not.activated'); ?> e-mail</div>
              <?php endif; ?>
            </div>
            <?php if ($user['limiting_mode'] == 1) : ?>
              <div class="red"><?= __('dumb.mode'); ?></div>
            <?php endif; ?>
            <?php if (!empty($user['banlist_int_num'])) : ?>
              <div class="red">bans: <?= $user['banlist_int_num']; ?></div>
            <?php endif; ?>
          </td>
          <td class="text-sm align-right">
            <a class="gray-600 ml10" href="<?= getUrlByName('admin.regip', ['ip' => $user['reg_ip']]); ?>">
              <?= $user['reg_ip']; ?>
            </a>
            <?php if ($user['duplicat_ip_reg'] > 1) : ?>
              <sup class="red">(<?= $user['duplicat_ip_reg']; ?>)</sup>
            <?php endif; ?>
            <br>
            <?= $user['created_at']; ?>
          </td>
          <td class="text-sm align-right">
            <?php if (!empty($user['last_visit_logs']['latest_ip'])) : ?>
              <a class="gray-600 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $user['last_visit_logs']['latest_ip']]); ?>">
                <?= $user['last_visit_logs']['latest_ip']; ?>
              </a>
              <br>
            <?php endif; ?>
            <?php if (!empty($user['last_visit_logs']['latest_date'])) : ?>
              <?= $user['last_visit_logs']['latest_date']; ?>
            <?php endif; ?>
          </td>
          <td class="center">
            <?php if ($user['trust_level'] != UserData::REGISTERED_ADMIN) : ?>
              <?php if ($user['ban_list']) : ?>
                <span class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
                  <span class="red"><?= __('unban'); ?></span>
                </span>
              <?php else : ?>
                <span class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
                  <?= __('ban.it'); ?>
                </span>
              <?php endif; ?>
            <?php else : ?>
              ---
            <?php endif; ?>
          </td>
          <td class="center">
            <a title="<?= __('edit'); ?>" href="<?= getUrlByName('admin.user.edit', ['id' => $user['id']]); ?>">
              <i class="bi-pencil"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('admin.users')); ?>
</div>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>