<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('admin'), '/admin/users', lang('users'), lang('search')); ?>
      <table>
        <thead>
          <th>N</th>
          <th><?= lang('information'); ?></th>
          <th>E-mail</th>
          <th><?= lang('sign up'); ?></th>
          <th>
              <?php if ($data['option'] == 'logs') { ?>
                  <?= lang('last'); ?>
              <?php } else { ?>
                  IP
              <?php } ?>
          </th>
          <th>Ban</th>
          <th><?= lang('action'); ?></th>
        </thead>
        <?php foreach ($data['results'] as $user) {  ?>
          <tr>
            <td class="center">
              <?= $user['user_id']; ?>
            </td>
            <td>
              <?= user_avatar_img($user['user_avatar'], 'small', $user['user_login'], 'ava'); ?>
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
                <?php if ($user['isBan']) { ?>
                  <div class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                    <span class="red"><?= lang('unban'); ?></span>
                  </div>
                <?php } else { ?>
                  <div class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                    <?= lang('ban it'); ?>
                  </div>
                <?php } ?>
              <?php } else { ?>
                ---
              <?php } ?>
            </td>
            <td class="center">
              <?php if ($user['user_trust_level'] != 5) { ?>
                <a title="<?= lang('edit'); ?>" href="/admin/user/<?= $user['user_id']; ?>/edit">
                  <i class="icon-pencil size-15"></i>
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