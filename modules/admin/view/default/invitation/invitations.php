<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box bg-white">
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
          <span class="lowercase text-sm">
            <?= $inv['invitation_email']; ?>
          </span>
          <sup>id<?= $inv['active_uid']; ?></sup>
          <span class="text-sm"> - <?= Html::langDate($inv['active_time']); ?>
          <?php else : ?>
            <span class="gray-600 lowercase text-sm">
              <?= $inv['invitation_email']; ?> &nbsp; <?= Html::langDate($inv['add_time']); ?>
            </span>
          <?php endif; ?>
          </span>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>