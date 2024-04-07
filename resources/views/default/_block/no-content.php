<?php if ($type == 'small') : ?>
  <div class="box gray-600 bg-violet mt15">
    <svg class="icons green">
      <use xlink:href="/assets/svg/icons.svg#<?= $icon; ?>"></use>
    </svg>
    <span class="middle"><?= $text; ?></span>
  </div>
<?php else : ?>
  <div id="no_content" class="box center gray-600">
    <svg class="icons icon-max">
      <use xlink:href="/assets/svg/icons.svg#<?= $icon; ?>"></use>
    </svg>
    <div><?= $text; ?></div>
  </div>
<?php endif; ?>