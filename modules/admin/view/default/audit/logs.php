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
        <th class="w160"><?= Translate::get('users'); ?></th>
        <th><?= Translate::get('type'); ?></th>
        <th><?= Translate::get('action'); ?></th>
        <th><?= Translate::get('time'); ?></th>
        <th><i class="bi bi-eye"></i></th>
      </thead>
      <?php foreach ($data['logs'] as $log) { ?>
        <tr>
          <td><?= $log['id']; ?></td>
          <td>
            <a href="<?= getUrlByName('profile', ['login' => $log['user_login']]); ?>">
              <?= $log['user_login']; ?>
            </a>
            <sup class="gray-400 ml5">id:<?= $log['user_id']; ?></sup>
          </td>
          <td class="gray-400"><?= Translate::get($log['type_content']); ?></td>
          <td><?= sprintf(Translate::get($log['action_name']), Translate::get($log['type_content'])); ?></td>
          <td class="gray-400"><?= lang_date($log['add_date']); ?></td>
          <th><a target="_blank" rel="noopener noreferrer" href="<?= $log['url_content']; ?>"><i class="bi bi-eye"></i></a></th>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>

  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.logs')); ?>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>