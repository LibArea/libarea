<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => 'add',
        'url'   => getUrlByName($data['type'] . '.add'),
        'name'  => Translate::get('add'),
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
          <?= Translate::get('remove'); ?>
        </a>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>