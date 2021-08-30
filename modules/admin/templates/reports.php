<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), '/admin/users', lang('Users'), $data['meta_title']); ?>

      <?php if ($reports) { ?>
        <table>
          <thead>
            <th class="center">N</th>
            <th><?= lang('Avatar'); ?></th>
            <th><?= lang('Information'); ?></th>
            <th><?= lang('Saw'); ?></th>
          </thead>
          <?php foreach ($reports as $report) {  ?>
            <tr>
              <td class="center">
                <?= $report['report_id']; ?>
                </span>
              <td>
                <a class="gray" href="/u/<?= $report['user']['user_login']; ?>">
                  <?= user_avatar_img($report['user']['user_avatar'], 'max', $report['user']['user_login'], 'ava-24 mr5'); ?>
                  <?= $report['user']['user_login']; ?>
                </a>
              </td>
              <td>
                <div class="size-13 gray lowercase">
                  <?= $report['report_type']; ?>
                  <span class="mr5 ml5"> &#183; </span>
                  <?= $report['date']; ?>
                </div>
                <div class="mt5">
                  <a href="<?= $report['report_url']; ?>"><?= $report['report_url']; ?></a>
                </div>
              </td>
              <td class="center<?php if ($report['report_status'] == 0) { ?> delleted<?php } ?>">
                <span class="report-status" data-id="<?= $report['report_id']; ?>">
                  <i class="icon-air gray size-21"></i>
                </span>
              </td>
            </tr>
          <?php } ?>
        </table>
      <?php } else { ?>
        <?= no_content('No users'); ?>
      <?php } ?>
      <?= pagination($data['pNum'], $data['pagesCount'], null, '/admin/reports'); ?>
    </div>
</div>
</main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>