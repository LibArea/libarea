<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box-white">
  <?php if ($data['logs']) { ?>
    <table>
      <thead>
        <th class="w60">N</th>
        <th class="w160"><?= __('users'); ?></th>
        <th><?= __('type'); ?></th>
        <th><?= __('action'); ?></th>
        <th><?= __('time'); ?></th>
        <th><i class="bi-eye"></i></th>
      </thead>
      <?php foreach ($data['logs'] as $log) { ?>
        <tr>
          <td><?= $log['id']; ?></td>
          <td>
            <a href="<?= getUrlByName('profile', ['login' => $log['user_login']]); ?>">
              <?= $log['user_login']; ?>
            </a>
            <sup class="gray-600 ml5">id:<?= $log['user_id']; ?></sup>
          </td>
          <td class="gray-600"><?= __($log['action_type']); ?></td>
          <td><?= sprintf(__($log['action_name']), __($log['action_type'])); ?></td>
          <td class="gray-600"><?= Html::langDate($log['add_date']); ?></td>
          <th><a target="_blank" rel="noopener noreferrer" href="<?= $log['url_content']; ?>"><i class="bi-eye"></i></a></th>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>

  <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.logs')); ?>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>