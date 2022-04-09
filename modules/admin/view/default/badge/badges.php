<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => 'add',
        'url' => getUrlByName($data['type'] . '.add'),
        'name' => __('add'),
        'icon' => 'bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="box-white">
  <?php if (!empty($data['badges'])) { ?>
    <table class="mt20">
      <thead>
        <th>Id</th>
        <th><?= __('icon'); ?></th>
        <th><?= __('title'); ?>&nbsp;/&nbsp;<?= __('description'); ?></th>
        <th><?= __('action'); ?></th>
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
            <a title="<?= __('edit'); ?>" href="/admin/badges/<?= $bg['badge_id']; ?>/edit">
              <i class="bi-pencil"></i>
            </a>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>
</div>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>