<?= insertTemplate('header', ['meta' => $meta]); ?>

<script src="/assets/js/catalog.js"></script>

<?php $code = $data['code']; ?>
<div id="contentWrapper" class="wrap justify-between">
  <main>
    <a class="text-sm" href="<?= url('web'); ?>">
      << <?= __('web.catalog'); ?></a>
        <span class="gray-600">/ <?= __('web.status'); ?></span>

        <div class="flex justify-between mt10 mb20">
          <?= insertTemplate('/admin/menu'); ?>
          <div class="status btn btn-outline-primary">
            <svg class="icon text-xl">
              <use xlink:href="/assets/svg/icons.svg#chart"></use>
            </svg>
            <?= __('web.refresh'); ?>
          </div>
        </div>

        <div class="mb20 text-sm">
          <a class="ml10 mr15 gray-600 <?php if ($code == 301) : ?> active<?php endif; ?>" href="<?= url('web.status', ['code' => 301]) ?>">301</a>
          <a class="mr15 gray-600<?php if ($code == 302) : ?> active<?php endif; ?>" href="<?= url('web.status', ['code' => 302]) ?>">302</a>
          <a class="mr15 gray-600<?php if ($code == 404 || $code == null) : ?> active<?php endif; ?>" href="<?= url('web.status', ['code' => 404]) ?>">404</a>
          <a class="gray-600<?php if ($code == 403) : ?> active<?php endif; ?>" href="<?= url('web.status', ['code' => 403]) ?>">403</a>
        </div>

        <?php foreach ($data['status'] as $st) : ?>
          <div class="gray text-sm">
            <a href="<?= url('website', ['id' => $st['item_id'], 'slug' => $st['item_slug']]); ?>">#<?= $st['item_id']; ?></a>

            <span class="green"><?= $st['item_url']; ?></span>
            <?= status_it($st['status_list']); ?>
          </div>
        <?php endforeach; ?>
  </main>
  <?= insertTemplate('/admin/sidebar'); ?>
</div>


<?php function status_it($list)
{
  $status_list = preg_split('/(@)/', $list ?? false);

  $result = [];
  foreach (array_chunk($status_list, 2) as $row) {
    $result[] = '' . $row[0] . ' - <small class="gray-600">' . $row[1] . '</small>  ';
  }

  return implode($result);
} ?>

<?= insertTemplate('footer'); ?>