<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <a class="right" title="<?= lang('Add'); ?>" href="/admin/badges/add">
        <i class="icon-plus middle"></i>
      </a>
      <?= breadcrumb('/admin', lang('Admin'), null, null, lang('Badges')); ?>

      <?php if (!empty($data['badges'])) { ?>
        <table>
          <thead>
            <th>Id</th>
            <th>Icon</th>
            <th><?= lang('Title'); ?>&nbsp;/&nbsp;<?= lang('Description'); ?></th>
            <th><?= lang('Action'); ?></th>
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
  </main>
</div>