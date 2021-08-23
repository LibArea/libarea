<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/admin', lang('Admin'), '/admin/users', lang('Users'), $data['meta_title']); ?>

        <div class="t-table">
          <div class="t-th">
            <span class="t-td center">N</span>
            <span class="t-td"><?= lang('Avatar'); ?></span>
            <span class="t-td"><?= lang('Information'); ?></span>
            <span class="t-td center"><?= lang('Saw'); ?></span>
          </div>
          <?php if ($reports) {  ?>
            <?php foreach ($reports as $report) {  ?>
              <div class="t-tr">
                <span class="t-td width-30 center">
                  <?= $report['report_id']; ?>
                </span>
                <span class="t-td width-30">
                  <a class="gray" href="/u/<?= $report['user']['user_login']; ?>">
                    <?= user_avatar_img($report['user']['user_avatar'], 'max', $report['user']['user_login'], 'ava-24 mr5'); ?>
                    <?= $report['user']['user_login']; ?>
                  </a>
                </span>
                <span class="t-td">
                  <div class="size-13 gray lowercase">
                    <?= $report['report_type']; ?>
                    <span class="mr5 ml5"> &#183; </span>
                    <?= $report['date']; ?>
                  </div>
                  <div class="mt5">
                    <a href="<?= $report['report_url']; ?>"><?= $report['report_url']; ?></a>
                  </div>
                </span>
                <span class="t-td center<?php if ($report['report_status'] == 0) { ?> delleted<?php } ?>">
                  <span class="report-status" data-id="<?= $report['report_id']; ?>">
                    <i class="icon-air gray size-21"></i>
                  </span>
                </span>
              </div>
            <?php } ?>

          <?php } else { ?>
            <?= no_content('No users'); ?>
          <?php } ?>
        </div>

        <?= pagination($data['pNum'], $data['pagesCount'], null, '/admin/reports'); ?>
      </div>
    </div>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>