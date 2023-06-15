<div id="contentWrapper" class="wrap wrap-max">
  <main class="w-100">
    <a class="text-sm" href="<?= url('web'); ?>">
      << <?= __('web.catalog'); ?></a>
        <span class="gray-600">/ <?= __('web.status'); ?></span>

        <div class="flex justify-between mt10 mb20">
          <?= insert('/content/item/admin/menu'); ?>
          <a class="btn btn-outline-primary" href="#">
            <svg class="icons text-xl">
              <use xlink:href="/assets/svg/icons.svg#chart"></use>
            </svg>
            <?= __('web.refresh'); ?>
          </a>
        </div>

        <div class="mb20 text-sm">
          <a class="ml10 gray-600 <?php if ($day == 1) : ?> active<?php endif; ?>" href="#">404</a>
          <a class="mr15 ml15 gray-600<?php if ($day == 3) : ?> active<?php endif; ?>" href="#">403</a>
          <a class="gray-600<?php if ($day == 'all' || !$day) : ?> active<?php endif; ?>" href="#">@</a>
        </div>

        <?php foreach ($data['status'] as $st) : ?>
          <div class="gray text-sm">
            ...
          </div>
        <?php endforeach; ?>
  </main>
  <?= insert('/content/item/admin/sidebar'); ?>
</div>