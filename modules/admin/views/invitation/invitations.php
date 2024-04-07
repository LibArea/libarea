<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<?php if (!empty($data['invitations'])) : ?>
  <?php foreach ($data['invitations'] as $key => $inv) : ?>
    <div>
      <a href="<?= url('profile', ['login' => $inv['uid']['login']]); ?>">
        <?= $inv['uid']['login']; ?>
      </a>
      <sup>id<?= $inv['uid']['id']; ?></sup>
      =>
      <?php if ($inv['login']) : ?>
        <a href="<?= url('profile', ['login' => $inv['login']]); ?>">
          <?= $inv['login']; ?>
        </a>
        <span class="lowercase gray-600 text-sm">
          <?= $inv['invitation_email']; ?>
          <sup>id<?= $inv['active_uid']; ?></sup>
          — <?= langDate($inv['uid']['updated_at']); ?>
        </span>
      <?php else : ?>
        <span class="gray-600 lowercase text-sm">1
          <?= $inv['invitation_email']; ?> — <?= langDate($inv['uid']['created_at']); ?>
        </span>
      <?php endif; ?>
      </span>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'info']); ?>
<?php endif; ?>

</main>
<?= insertTemplate('footer'); ?>