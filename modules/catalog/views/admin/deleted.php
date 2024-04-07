<?= insertTemplate('header', ['meta' => $meta]); ?>

<div id="contentWrapper" class="wrap justify-between">
  <main>
    <a class="text-sm" href="<?= url('web'); ?>">
      << <?= __('web.catalog'); ?></a>
        <span class="gray-600">/ <?= __('web.deleted'); ?></span>

        <div class="flex justify-between mt10 mb20">
          <?= insertTemplate('/admin/menu'); ?>
        </div>

        <?php if (!empty($data['items'])) : ?>
          <?= insertTemplate('/item/card', ['data' => $data, 'sort' => 'all']); ?>
        <?php else : ?>
          <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no_website'), 'icon' => 'info']); ?>
        <?php endif; ?>
  </main>
  <?= insertTemplate('/admin/sidebar'); ?>
</div>

<?= insertTemplate('footer'); ?>