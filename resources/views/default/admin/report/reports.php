<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), '/admin/users', lang('users'), lang('Reports')); ?>

  <?php if ($data['reports']) { ?>
    <table class="mt20">
      <thead>
        <th class="center">N</th>
        <th><?= lang('avatar'); ?></th>
        <th><?= lang('information'); ?></th>
        <th><?= lang('saw'); ?></th>
      </thead>
      <?php foreach ($data['reports'] as $report) {  ?>
        <tr>
          <td class="center">
            <?= $report['report_id']; ?>
            </span>
          <td>
            <a class="gray" href="<?= getUrlByName('user', ['login' => $report['user']['user_login']]); ?>">
              <?= user_avatar_img($report['user']['user_avatar'], 'max', $report['user']['user_login'], 'w24 mr5'); ?>
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
          <td class="center<?php if ($report['report_status'] == 0) { ?> bg-red-100<?php } ?>">
            <span class="report-status" data-id="<?= $report['report_id']; ?>">
              <i class="icon-air gray size-21"></i>
            </span>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'no users']); ?>
  <?php } ?>
  <?= pagination($data['pNum'], $data['pagesCount'], null, '/admin/reports'); ?>
  </div>
</main>