<?php if ($sheet == 'top') :
     $day = Request::getGet('sort_day'); ?>
 
  <div class="mb20 text-sm">
    <a class="ml10 gray-600 <?php if ($day == 1) : ?> active<?php endif; ?>" href="./top?sort_day=1"><?= __('app.one_month'); ?></a>
    <a class="mr15 ml15 gray-600<?php if ($day == 3) : ?> active<?php endif; ?>" href="./top?sort_day=3"><?= __('app.three_months'); ?></a>
    <a class="gray-600<?php if ($day == 'all' || !$day) : ?> active<?php endif; ?>" href="./top?sort_day=all"><?= __('app.all_time'); ?></a>
  </div>
<?php endif; ?>