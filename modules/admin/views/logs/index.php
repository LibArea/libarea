<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'audits.all',
        'url'   => url('admin.logs'),
        'name'  => __('admin.logs'),
      ], [
        'id'    => 'audits.all',
        'url'   => url('admin.logs.search'),
        'name'  => __('admin.search'),
      ]
    ]
  ]
); ?>

<div class="box bg-white">
  <?php if ($data['logs']) : ?>
    <table>
      <thead>
        <th class="w60">N</th>
        <th class="w160"><?= __('admin.users'); ?></th>
        <th><?= __('admin.type'); ?></th>
        <th><?= __('admin.action'); ?></th>
        <th><?= __('admin.time'); ?></th>
        <th class="w30"><svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#eye"></use>
          </svg></th>
      </thead>
      <?php foreach ($data['logs'] as $log) : ?>
        <tr>
          <td><?= $log['id']; ?></td>
          <td>
            <a href="<?= url('profile', ['login' => $log['user_login']]); ?>">
              <?= $log['user_login']; ?>
            </a>
            <sup class="gray-600 ml5">id:<?= $log['user_id']; ?></sup>
          </td>
          <td class="gray-600"><?= __('admin.' . $log['action_type']); ?></td>
          <td><?= __('admin.' . $log['action_name'], ['name' => __('admin.' . $log['action_type'])]); ?></td>
          <td class="gray-600"><?= langDate($log['add_date']); ?></td>
          <td><a target="_blank" rel="noopener noreferrer" href="<?= $log['url_content']; ?>"><svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#eye"></use>
              </svg></a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'info']); ?>
  <?php endif; ?>

  <?= pagination($data['pNum'], $data['pagesCount'], false, url('admin.logs')); ?>
</div>
</main>
<?= insertTemplate('footer'); ?>