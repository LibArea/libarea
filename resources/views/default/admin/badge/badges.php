<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/admin', ['sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="white-box pt5 pr15 pb5 pl15">
    <a class="right" title="<?= Translate::get('add'); ?>" href="/admin/badges/add">
      <i class="bi bi-plus-lg middle"></i>
    </a>

    <?= breadcrumb(
      getUrlByName('admin'),
      Translate::get('admin'),
      null,
      null,
      Translate::get('badges')
    ); ?>

    <div class="bg-white br-box-gray p15">
      <?php if (!empty($data['badges'])) { ?>
        <table class="mt20">
          <thead>
            <th>Id</th>
            <th>Icon</th>
            <th><?= Translate::get('title'); ?>&nbsp;/&nbsp;<?= Translate::get('description'); ?></th>
            <th><?= Translate::get('action'); ?></th>
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
                <a title="<?= Translate::get('edit'); ?>" href="/admin/badges/<?= $bg['badge_id']; ?>/edit">
                  <i class="bi bi-pencil size-15"></i>
                </a>
              </td>
            </tr>
          <?php } ?>
        </table>
      <?php } else { ?>
        <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
      <?php } ?>
    </div>
  </div>
</main>