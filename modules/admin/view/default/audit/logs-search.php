<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'logssearch.all',
        'url'   => url('admin.logs.search'),
        'name'  => __('search'),
        'icon'  => 'bi-search',
      ]
    ]
  ]
); ?>

<div class="box bg-white">
  <?php if ($data['logs']) : ?>
    <table>
      <thead>
        <th><?= __('request'); ?></th>
        <th><?= __('time'); ?></th>
        <th class="w60"><?= __('results'); ?></th>
      </thead>
      <?php foreach ($data['logs'] as $log) : ?> 
        <tr>
          <td class="gray-600">
            <a target="_blank" rel="noreferrer" href="/search/go?q=<?= $log['request']; ?>&type=<?= $log['action_type']; ?>">
              <?= $log['request']; ?>
            </a>
          </td>
          <td class="gray-600"> <?= __($log['action_type']); ?> | <?= Html::langDate($log['add_date']); ?></td>
          <td class="center">
            <?= $log['count_results']; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>