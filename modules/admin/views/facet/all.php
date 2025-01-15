<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<?php foreach ($data['types_facets'] as $type) : ?>
  <a class="flex gap-sm items-center mb10" href="<?= url('admin.facets.type', ['type' => $type['type_code']]); ?>">
    <svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#circle"></use>
    </svg>
    <?= __('admin.' . $type['type_lang']); ?>
    <span class="gray-600">(<?= $data['count']['count_' . $type['type_code']]; ?>)</span>
  </a>
<?php endforeach; ?>

</main>

<?= insertTemplate('footer'); ?>