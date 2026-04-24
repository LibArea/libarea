<?php if ($type == 'small') : ?>
  <div class="box">
    <?= icon('icons', $icon, 25, 'icon green'); ?>
    <span class="middle"><?= $text; ?>.</span>
  </div>
<?php else : ?>
  <div id="no_content" class="p15 center gray-600">
    <?= icon('icons', $icon, 128); ?>
    <div><?= $text; ?></div>
  </div>
<?php endif; ?>