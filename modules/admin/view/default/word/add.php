<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<form class="max-w780" action="<?= url('admin.word.create'); ?>" method="post">
  <?= csrf_field() ?>
  <fieldset>
    <label for="word"><?= __('admin.word'); ?></label>
    <input type="text" name="word">
  </fieldset>
  <?= Html::sumbit(__('admin.add')); ?>
</form>

</main>
<?= includeTemplate('/view/default/footer'); ?>