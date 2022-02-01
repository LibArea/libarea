<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <?php if ($data['logs']) { ?>
    <table>
      <thead>
        <th class="left">N</th>
        <th><?= Translate::get('users'); ?></th>
        <th><?= Translate::get('type'); ?></th>
        <th><?= Translate::get('action'); ?></th>
        <th><?= Translate::get('time'); ?></th>
        <th><i class="bi bi-eye"></i></th>
      </thead>
      <?php foreach ($data['logs'] as $log) { ?>
        <tr>
          <td><?= $log['log_id']; ?></td>
          <td>
            <span class="gray-400 mr15">id: <?= $log['log_user_id']; ?></span>
            <a href="<?= getUrlByName('profile', ['login' => $log['log_user_login']]); ?>"><?= $log['log_user_login']; ?></a>
          </td>
          <td class="gray-400"><?= Translate::get($log['log_type_content']); ?></td>
          <td><?= sprintf(Translate::get($log['log_action_name']), Translate::get($log['log_type_content'])); ?></td>
          <td class="gray-400"><?= lang_date($log['log_date']); ?></td>
          <th><a target="_blank" rel="noopener noreferrer" href="<?= $log['log_url_content']; ?>"><i class="bi bi-eye"></i></a></th>
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