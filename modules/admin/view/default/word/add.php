<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => 'add',
        'url' => getUrlByName($data['type'] . '.add'),
        'name' => Translate::get('add'),
        'icon' => 'bi bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="box-white max-w780">
  <form action="<?= getUrlByName('admin.word.create'); ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label for="word"><?= Translate::get('word'); ?></label>
      <input type="text" name="word">
    </fieldset>
    <?= Html::sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>