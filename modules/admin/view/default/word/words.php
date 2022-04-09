<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'add',
        'url'   => getUrlByName($data['type'] . '.add'),
        'name'  => __('add'),
        'icon'  => 'bi bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="box-white">
  <?php if (!empty($data['words'])) { ?>
    <?php foreach ($data['words'] as $key => $word) { ?>
      <div class="content-telo">
        <?= $word['stop_word']; ?> |
        <a data-id="<?= $word['stop_id']; ?>" data-type="word" class="type-ban lowercase text-sm">
          <?= __('remove'); ?>
        </a>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>