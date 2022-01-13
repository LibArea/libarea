<?= import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'uid'   => $uid,
    'menus' => []
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
          <?= user_avatar_img($user['user_avatar'], 'small', $user['user_login'], 'w20 h20 mr5'); ?>
          <a href="<?= getUrlByName('profile', ['login' => $user['user_login']]); ?>">
            <?= $user['user_login']; ?>
          </a>
          <?php if ($user['user_name']) { ?>
            (<?= $user['user_name']; ?>)
          <?php } ?>
          <sup class="red-500">TL:<?= $user['user_trust_level']; ?></sup>

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
            <br> <sup class="red-500">(<?= $user['duplicat_ip_reg']; ?>)</sup>
          <?php } ?>
        </td>
        <td class="center">
          <?php if ($user['user_trust_level'] != UserData::REGISTERED_ADMIN) { ?>
            <?php if ($user['user_ban_list']) { ?>
              <div class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                <span class="red-500"><?= Translate::get('unban'); ?></span>
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
          <?php if ($user['user_trust_level'] != UserData::REGISTERED_ADMIN) { ?>
            <a title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('admin.user.edit', ['id' => $user['user_id']]); ?>">
              <i class="bi bi-pencil"></i>
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