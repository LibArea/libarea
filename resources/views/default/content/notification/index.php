<main>
  <div class="box">
    <?= __('app.notifications'); ?>
    <a href="<?= url('notif.remove'); ?>" class="right">
      <?= Html::sumbit(__('app.i_read')); ?>
    </a>
  </div>

  <div class="bg-white mb15">
    <?php if (!empty($data['notifications'])) : ?>
	  <?= insert('/content/notification/cell', ['data' => $data, 'size' => '']); ?>
      <div class="p15 center gray-600"><?= __('app.notifications_limit'); ?></div>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'info']); ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('help.notifications_info'); ?>
  </div>
</aside>