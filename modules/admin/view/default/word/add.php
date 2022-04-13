<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => 'add',
        'url' => getUrlByName($data['type'] . '.add'),
        'name' => __('add'),
        'icon' => 'bi bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="box max-w780">
  <form action="<?= getUrlByName('admin.word.create'); ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label for="word"><?= __('word'); ?></label>
      <input type="text" name="word">
    </fieldset>
    <?= Html::sumbit(__('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>