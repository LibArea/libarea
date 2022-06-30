<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<a class="sky mb15" href="<?= url('admin.badges.add'); ?>"><?= __('admin.add'); ?></a>

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
            <svg class="icons">
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
<?= includeTemplate('/view/default/footer'); ?>