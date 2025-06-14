<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box">
  <form class="max-w-md" action="<?= url('admin.word.create', method: 'post'); ?>" method="post">
    <?= $container->csrf()->field(); ?>
    <fieldset>
      <label for="word"><?= __('admin.word'); ?></label>
      <input type="text" name="word">
    </fieldset>
    <?= Html::sumbit(__('admin.add')); ?>
  </form>
</div>
</main>
<?= insertTemplate('footer'); ?>