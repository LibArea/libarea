<div class="dropdown notif block">
  <span class="right-close pointer">x</span>
  <div class="mt10">
    <?php if (!empty($data['notifications'])) : ?>
      <?= insert('/content/notification/cell', ['data' => $data, 'size' => 'text-sm']); ?>
      <div class="p15 lowercase">
        <a href="<?= url('notifications'); ?>"><?= __('app.notifications_page'); ?></a>
        <a href="<?= url('notif.remove'); ?>" class="right gray-600">
          <?= __('app.i_read'); ?>
        </a>
      </div>
    <?php else : ?>
      <div class="text-sm">
        <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'info']); ?>
      </div>
      <div class="p15 center">
        <a href="<?= url('notifications'); ?>"><?= __('app.notifications_page'); ?></a>
      </div>
    <?php endif; ?>
  </div>
</div>