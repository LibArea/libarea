<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => url('admin.' . $data['type']),
        'name' => __('admin.all') . ' (' . $data['users_count'] . ')',
      ], [
        'id' => $data['type'] . '.ban',
        'url' => url('admin.' . $data['type'] . '.ban'),
        'name' => __('admin.deleted'),
      ]
    ]
  ]
); ?>

<?php if ($data['alluser']) :  ?>
  <table>
    <thead>
      <th>id</th>
      <th><?= __('admin.avatar'); ?></th>
      <th><?= __('admin.information'); ?></th>
      <th class="center mb-none">IP <?= __('admin.registrations'); ?></th>
      <th class="right mb-none"><?= __('admin.last'); ?></th>
      <th class="center"><?= __('admin.action'); ?></th>
      <th class="center"><?= __('admin.edit'); ?></th>
    </thead>
    <?php foreach ($data['alluser'] as $user) :  ?>
      <tr>
        <td class="center">
          <?= $user['id']; ?>
        </td>
        <td class="center">
          <?= Img::avatar($user['avatar'], $user['login'], 'img-lg', 'max'); ?>
        </td>
        <td>
          <a href="<?= url('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
          <?php if ($user['name']) : ?>
            (<?= $user['name']; ?>)
          <?php endif; ?>
          <sup class="gray">TL:<?= $user['trust_level']; ?></sup>
          <?php if ($user['invitation_id'] != 0) : ?><sup>+ inv. id<?= $user['invitation_id']; ?></sup><?php endif; ?>
          <?php if ($user['whisper']) : ?>
            <span title="<?= $user['whisper']; ?>" class="tips text-sm gray-600">
              <svg class="icons green">
                <use xlink:href="/assets/svg/icons.svg#info"></use>
              </svg>
            </span>
          <?php endif; ?>
          <div class="text-sm">
            <?= $user['email']; ?>
            <?php if ($user['activated'] == 1) : ?>
              <div class="gray-600"><?= __('admin.activated'); ?></div>
            <?php else : ?>
              <div class="red"><?= __('admin.not_activ_email'); ?></div>
            <?php endif; ?>
          </div>
          <?php if ($user['limiting_mode'] == 1) : ?>
            <div class="red"><?= __('admin.dumb_mode'); ?></div>
          <?php endif; ?>
          <?php if (!empty($user['banlist_int_num'])) : ?>
            <div class="red">bans: <?= $user['banlist_int_num']; ?></div>
          <?php endif; ?>
        </td>
        <td class="text-sm align-right mb-none">
          <a class="gray-600" href="<?= url('admin.regip', ['ip' => $user['reg_ip']]); ?>">
            <?= $user['reg_ip']; ?>
          </a>
          <?php if ($user['duplicat_ip_reg'] > 1) : ?>
            <sup class="red">(<?= $user['duplicat_ip_reg']; ?>)</sup>
          <?php endif; ?>
          <br>
          <?= $user['created_at']; ?>
        </td>
        <td class="text-sm align-right mb-none">
          <?php if (!empty($user['last_visit_logs']['latest_ip'])) : ?>
            <a class="gray-600" href="<?= url('admin.logip', ['ip' => $user['last_visit_logs']['latest_ip']]); ?>">
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
                <span class="red"><?= __('admin.unban'); ?></span>
              </span>
            <?php else : ?>
              <span class="type-ban sky" data-id="<?= $user['id']; ?>" data-type="user">
                <?= __('admin.ban'); ?>
              </span>
            <?php endif; ?>
          <?php else : ?>
            ---
          <?php endif; ?>
        </td>
        <td class="center">
          <a title="<?= __('admin.edit'); ?>" href="<?= url('admin.user.edit', ['id' => $user['id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'info']); ?>
<?php endif; ?>
<?= Html::pagination($data['pNum'], $data['pagesCount'], null, url('admin.users')); ?>

</main>
<?= includeTemplate('/view/default/footer'); ?>