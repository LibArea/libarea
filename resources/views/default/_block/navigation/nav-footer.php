<li>
  <a href="<?= url('page', ['facet_slug' => 'info', 'slug' => 'information']); ?>">
    <?= icon('icons', 'chevrons-right'); ?>
    <div class="nav-overflow"><?= __('app.information'); ?></div>
  </a>
</li>
<li>
  <a href="<?= url('page', ['facet_slug' => 'info', 'slug' => 'donate']); ?>">
    <?= icon('icons', 'donate', '24', 'red'); ?>
    <div class="nav-overflow"><?= __('app.donate'); ?></div>
  </a>
</li>