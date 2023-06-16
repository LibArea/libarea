<?php $code = $data['code']; ?>
<div id="contentWrapper" class="wrap wrap-max">
  <main class="w-100">
    <a class="text-sm" href="<?= url('web'); ?>">
      << <?= __('web.catalog'); ?></a>
        <span class="gray-600">/ <?= __('web.status'); ?></span>

        <div class="flex justify-between mt10 mb20">
          <?= insert('/content/item/admin/menu'); ?>
          <div class="status btn btn-outline-primary">
            <svg class="icons text-xl">
              <use xlink:href="/assets/svg/icons.svg#chart"></use>
            </svg>
            <?= __('web.refresh'); ?>
          </div>
        </div>

        <div class="mb20 text-sm">
          <a class="ml10 gray-600 <?php if ($code != 404 || $code == 403) : ?> active<?php endif; ?>" href="<?= url('web.status', ['code' => 404]) ?>"><?= __('web.all'); ?></a>
          <a class="mr15 ml15 gray-600<?php if ($code == 404) : ?> active<?php endif; ?>" href="<?= url('web.status', ['code' => 403]) ?>">404</a>
          <a class="gray-600<?php if ($code == 403) : ?> active<?php endif; ?>" href="<?= url('web.status') ?>">403</a>
        </div>

        <?php foreach ($data['status'] as $st) : ?>
          <div class="gray text-sm">
            ...
          </div>
        <?php endforeach; ?>
  </main>
  <?= insert('/content/item/admin/sidebar'); ?>
</div>