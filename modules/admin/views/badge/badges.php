<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => url('admin.' . $data['type']),
        'name' => __('admin.all'),
      ], [
        'id' => $data['type'] . '.all',
        'url' => url('admin.badges.add'),
        'name' => __('admin.add'),
      ]
    ]
  ]
); ?>

<?php if (!empty($data['badges'])) : ?>
  <table class="mt20">
    <thead>
      <th>Id</th>
      <th><?= __('admin.icon'); ?></th>
      <th><?= __('admin.title'); ?>&nbsp;/&nbsp;<?= __('admin.description'); ?></th>
      <th><?= __('admin.action'); ?></th>
    </thead>
    <?php foreach ($data['badges'] as $key => $bg) : ?>
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
          <a title="<?= __('admin.edit'); ?>" href="<?= url('admin.badges.edit', ['id' => $bg['badge_id']]); ?>">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'info']); ?>
<?php endif; ?>
</main>
<?= insertTemplate('footer'); ?>