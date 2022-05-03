<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => 'add',
        'url' => url($data['type'] . '.add'),
        'name' => __('admin.add'),
        'icon' => 'bi bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="box bg-white max-w780">
  <form action="<?= url('admin.word.create'); ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label for="word"><?= __('admin.word'); ?></label>
      <input type="text" name="word">
    </fieldset>
    <?= Html::sumbit(__('admin.add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>