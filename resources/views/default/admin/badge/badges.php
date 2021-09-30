<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="white-box pt5 pr15 pb5 pl15">
    <a class="right" title="<?= lang('add'); ?>" href="/admin/badges/add">
      <i class="icon-plus middle"></i>
    </a>
    <?= breadcrumb('/admin', lang('admin'), null, null, lang('badges')); ?>

    <?php if (!empty($data['badges'])) { ?>
      <table class="mt20">
        <thead>
          <th>Id</th>
          <th>Icon</th>
          <th><?= lang('title'); ?>&nbsp;/&nbsp;<?= lang('description'); ?></th>
          <th><?= lang('action'); ?></th>
        </thead>
        <?php foreach ($data['badges'] as $key => $bg) { ?>
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
              <a title="<?= lang('edit'); ?>" href="/admin/badges/<?= $bg['badge_id']; ?>/edit">
                <i class="icon-pencil size-15"></i>
              </a>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
    <?php } ?>
  </div>
</main>