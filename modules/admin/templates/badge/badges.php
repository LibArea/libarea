<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <a class="right" title="<?= lang('Add'); ?>" href="/admin/badges/add">
          <i class="icon-plus middle"></i>
        </a>
        <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

        <?php if (!empty($badges)) { ?>
          <table>
            <thead>
              <th>Id</th>
              <th>Icon</th>
              <th><?= lang('Title'); ?>&nbsp;/&nbsp;<?= lang('Description'); ?></th>
              <th><?= lang('Action'); ?></th>
            </thead>
            <?php foreach ($badges as $key => $bg) { ?>
              <tr>
                <td class="center">
                  <?= $bg['badge_id']; ?>
                </td>
                <td class="center">
                  <?= $bg['badge_icon']; ?>
                </td>
                <td>
                  <b><?= $bg['badge_title']; ?></b>
                  <br>
                  <?= $bg['badge_description']; ?>
                </td>
                <td class="center">
                  <a title="<?= lang('Edit'); ?>" href="/admin/badges/<?= $bg['badge_id']; ?>/edit">
                    <i class="icon-pencil size-15"></i>
                  </a>
                </td>
              </tr>
            <?php } ?>
          </table>
        <?php } else { ?>
          <?= no_content('No'); ?>
        <?php } ?>
      </div>
    </div>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>