<?php if ($type == 'small') : ?>
  <div class="box gray-600 bg-violet">
    <i class="<?= $icon; ?> green middle mr5"></i>
    <span class="middle"><?= $text; ?>...</span>
  </div>
<?php else : ?>   
  <div class="box center gray-600">
    <svg class="icons icon-max"><use xlink:href="/assets/svg/icons.svg#<?= $icon; ?>"></use></svg>
    <div><?= $text; ?></div>
  </div>
<?php endif; ?>