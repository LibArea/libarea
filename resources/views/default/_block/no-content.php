<?php if ($type == 'small') { ?>
  <div class="box gray-600 bg-violet-50">
    <i class="<?= $icon; ?> green middle mr5"></i>
    <span class="middle"><?= $text; ?>...</span>
  </div>
<?php } else { ?>   
  <div class="p20 center gray-600">
    <i class="<?= $icon; ?> block text-8xl"></i>
    <?= $text; ?>
  </div>
<?php } ?>