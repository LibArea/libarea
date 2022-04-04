<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="box-white">
  <table>
    <thead>
      <th>N</th>
      <th><?= Translate::get('information'); ?></th>
      <th>E-mail</th>
      <th><?= Translate::get('registration'); ?></th>
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
          <?= $user['id']; ?>
        </td>
        <td>
          <?= Html::image($user['avatar'], $user['login'], 'ava-sm', 'avatar', 'small'); ?>
          <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
            <?= $user['login']; ?>
          </a>
          <?php if ($user['name']) { ?>
            (<?= $user['name']; ?>)
          <?php } ?>
          <sup class="red">TL:<?= $user['trust_level']; ?></sup>

        </td>
        <td>
          <span class="date"><?= $user['email']; ?></span>
        </td>
        <td>
          <?= $user['created_at']; ?>
        </td>
        <td>
          <?php if ($data['option'] == 'logs') { ?>
            <?= $user['latest_date']; ?>
          <?php } else { ?>
            <?= $user['reg_ip']; ?>
          <?php } ?>

          <?php if ($user['duplicat_ip_reg'] > 1) { ?>
            <br> <sup class="red">(<?= $user['duplicat_ip_reg']; ?>)</sup>
          <?php } ?>
        </td>
        <td class="center">
          <?php if ($user['trust_level'] != UserData::REGISTERED_ADMIN) { ?>
            <?php if ($user['ban_list']) { ?>
              <div class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
                <span class="red"><?= Translate::get('unban'); ?></span>
              </div>
            <?php } else { ?>
              <div class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
                <?= Translate::get('ban.it'); ?>
              </div>
            <?php } ?>
          <?php } else { ?>
            ---
          <?php } ?>
        </td>
        <td class="center">
          <?php if ($user['trust_level'] != UserData::REGISTERED_ADMIN) { ?>
            <a title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('admin.user.edit', ['id' => $user['id']]); ?>">
              <i class="bi-pencil"></i>
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
<?= includeTemplate('/view/default/footer'); ?>