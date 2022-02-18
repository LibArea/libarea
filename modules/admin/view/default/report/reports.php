<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box-white">
  <?php if ($data['reports']) { ?>
    <table>
      <thead>
        <th class="center">N</th>
        <th><?= Translate::get('avatar'); ?></th>
        <th><?= Translate::get('information'); ?></th>
        <th><?= Translate::get('saw'); ?></th>
      </thead>
      <?php foreach ($data['reports'] as $report) { ?>
        <tr>
          <td class="center">
            <?= $report['report_id']; ?>
            </span>
          <td>
            <a class="gray" href="<?= getUrlByName('profile', ['login' => $report['user']['login']]); ?>">
              <?= user_avatar_img($report['user']['avatar'], 'max', $report['user']['login'], 'ava-base'); ?>
              <?= $report['user']['login']; ?>
            </a>
          </td>
          <td>
            <div class="text-sm gray lowercase">
              <?= Translate::get($report['report_type']); ?>
              <span class="mr5 ml5"> &#183; </span>
              <?= $report['date']; ?>
            </div>
            <p>
              <a href="<?= $report['report_url']; ?>"><?= $report['report_url']; ?></a>
            </p>
          </td>
          <td class="center<?php if ($report['report_status'] == 0) { ?> bg-red-200<?php } ?>">
            <span class="report-status" data-id="<?= $report['report_id']; ?>">
              <i class="bi bi-record-circle gray text-2xl"></i>
            </span>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
  <?= pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('admin.reports')); ?>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>