<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<?= insertTemplate('/user/info'); ?>

<table>
  <thead>
    <th><?= __('admin.information'); ?></th>
    <th class="center">Ban</th>
    <th class="center"><?= __('admin.action'); ?></th>
  </thead>
  <?php foreach ($data['results'] as $user) :  ?>
    <tr>
      <td>
        <?= Img::avatar($user['avatar'], $user['login'], 'img-sm', 'small'); ?>
        <a href="<?= url('profile', ['login' => $user['login']]); ?>">
          <?= $user['login']; ?>
        </a> (id <?= $user['id']; ?>)
        <?php if ($user['name']) : ?>
          (<?= $user['name']; ?>)
        <?php endif; ?>
        <sup class="gray-600">TL:<?= $user['trust_level']; ?></sup>
        <?php if (!empty($user['device_id'])) : ?>
          <sup class="red"><?= $user['device_id']; ?></sup>
        <?php endif; ?>
        <div class="gray-600">
          <?= $user['email']; ?> |
          <?= $user['created_at']; ?> |
          <?php if ($data['option'] == 'logs') : ?>
            <?= $user['latest_date']; ?>
          <?php else : ?>
            <?= $user['reg_ip']; ?>
          <?php endif; ?>
          <?php if ($user['duplicat_ip_reg'] > 1) : ?>
            <sup class="red"> (<?= $user['duplicat_ip_reg']; ?>)</sup>
          <?php endif; ?>
        </div>
      </td>
      <td class="center">
        <?php if ($user['trust_level'] == 10) : ?>
          ---
        <?php else : ?>
          <?php if ($user['ban_list']) : ?>
            <div class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
              <span class="red"><?= __('admin.unban'); ?></span>
            </div>
          <?php else : ?>
            <div class="type-ban" data-id="<?= $user['id']; ?>" data-type="user">
              <span class="gray-600"><?= __('admin.ban'); ?></span>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </td>
      <td class="center">
        <?php if ($user['trust_level'] == 10) : ?>
          ---
        <?php else : ?>
          <a title="<?= __('admin.edit'); ?>" href="<?= url('admin.user.edit.form', ['id' => $user['id']]); ?>">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
</main>
</div>
<?= insertTemplate('footer'); ?>