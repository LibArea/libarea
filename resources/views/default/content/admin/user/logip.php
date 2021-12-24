<div class="sticky mt5 top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.admin'),
      ); ?>
</div>

<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'pages'   => []
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <table>
    <thead>
      <th>N</th>
      <th><?= Translate::get('information'); ?></th>
      <th>E-mail</th>
      <th><?= Translate::get('sign up'); ?></th>
      <th>
        <?php if ($data['option'] == 'logs') { ?>
          <?= Translate::get('last'); ?>
        <?php } else { ?>
          IP
        <?php } ?>
      </th>
      <th>Ban</th>
      <th><?= Translate::get('action'); ?></th>
    </thead>
    <?php foreach ($data['results'] as $user) {  ?>
      <tr>
        <td class="center">
          <?= $user['user_id']; ?>
        </td>
        <td>
          <?= user_avatar_img($user['user_avatar'], 'small', $user['user_login'], 'w21 mr5'); ?>
          <a href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>">
            <?= $user['user_login']; ?>
          </a>
          <?php if ($user['user_name']) { ?>
            (<?= $user['user_name']; ?>)
          <?php } ?>
          <sup class="red">TL:<?= $user['user_trust_level']; ?></sup>

        </td>
        <td>
          <span class="date"><?= $user['user_email']; ?></span>
        </td>
        <td>
          <?= $user['user_created_at']; ?>
        </td>
        <td>
          <?php if ($data['option'] == 'logs') { ?>
            <?= $user['latest_date']; ?>
          <?php } else { ?>
            <?= $user['user_reg_ip']; ?>
          <?php } ?>

          <?php if ($user['duplicat_ip_reg'] > 1) { ?>
            <br> <sup class="red">(<?= $user['duplicat_ip_reg']; ?>)</sup>
          <?php } ?>
        </td>
        <td class="center">
          <?php if ($user['user_trust_level'] != 5) { ?>
            <?php if ($user['user_ban_list']) { ?>
              <div class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                <span class="red"><?= Translate::get('unban'); ?></span>
              </div>
            <?php } else { ?>
              <div class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                <?= Translate::get('ban it'); ?>
              </div>
            <?php } ?>
          <?php } else { ?>
            ---
          <?php } ?>
        </td>
        <td class="center">
          <?php if ($user['user_trust_level'] != 5) { ?>
            <a title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('admin.user.edit', ['id' => $user['user_id']]); ?>">
              <i class="bi bi-pencil size-15"></i>
            </a>
          <?php } else { ?>
            ---
          <?php } ?>
        </td>
      </tr>
    <?php } ?>
  </table>
</div>
</main>
</div>