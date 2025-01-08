<div class="max-w-md mt10">
  <?php if ($container->user()->active()) : ?>
    <form action="<?= url('edit.' . $data['type'], method: 'post'); ?>" accept-charset="UTF-8" method="post">
      <?= $container->csrf()->field(); ?>
      <textarea rows="5" minlength="6" name="content"><?= $data['content']; ?></textarea>
      <fieldset>
        <input type="hidden" name="id" value="<?= $data['id']; ?>">
        <?= Html::sumbit(__('app.edit')); ?>
        <span id="cancel" class="text-sm inline ml5 gray"><?= __('app.cancel'); ?></span>
      </fieldset>
    </form>
  <?php endif; ?>
</div>