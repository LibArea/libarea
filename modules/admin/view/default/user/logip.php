<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<table>
  <thead>
    <th>N</th>
    <th><?= __('admin.information'); ?></th>
    <th>E-mail</th>
    <th><?= __('admin.registration'); ?></th>
    <th>
      <?php if ($data['option'] == 'logs') : ?>
        <?= __('admin.last'); ?>
      <?php else : ?>
        IP
      <?php endif; ?>
    </th>
    <th>Ban</th>
    <th><?= __('admin.action'); ?></th>
  </thead>
  <?php foreach ($data['results'] as $user) :  ?>
    <tr>
      <td class="center">
        <?= $user['id']; ?>
      </td>
      <td>
        <?= Html::image($user['avatar'], $user['login'], 'img-sm', 'avatar', 'small'); ?>
        <a href="<?= url('profile', ['login' => $user['login']]); ?>">
          <?= $user['login']; ?>
        </a>
        <?php if ($user['name']) : ?>
          (<?= $user['name']; ?>)
        <?php endif; ?>
        <sup class="red">TL:<?= $user['trust_level']; ?></sup>
      </td>
      <td>
        <span class="date"><?= $user['email']; ?></span>
      </td>
      <td>
        <?= $user['created_at']; ?>
      </td>
      <td>
        <?php if ($data['option'] == 'logs') : ?>
          <?= $user['latest_date']; ?>
        <?php else : ?>
          <?= $user['reg_ip']; ?>
        <?php endif; ?>

        <?php if ($user['duplicat_ip_reg'] > 1) : ?>
          <br> <sup class="red">(<?= $user['duplicat_ip_reg']; ?>)</sup>
        <?php endif; ?>
      </td>
      <td class="center">
        <?php if (UserData::checkAdmin()) : ?>
          ---
        <?php else : ?>
          <?php if ($user['ban_list']) : ?>
            <div class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
              <span class="red"><?= __('admin.unban'); ?></span>
            </div>
          <?php else : ?>
            <div class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
              <?= __('admin.ban'); ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </td>
      <td class="center">
        <?php if (UserData::checkAdmin()) : ?>
          ---
        <?php else : ?>
          <a title="<?= __('admin.edit'); ?>" href="<?= url('admin.user.edit', ['id' => $user['id']]); ?>">
            <i class="bi-pencil"></i>
          </a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
</main>
</div>
<?= includeTemplate('/view/default/footer'); ?>