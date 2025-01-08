<div class="cm_addentry max-w-md mt10">
  <?php if ($container->user()->active()) : ?>
    <form action="<?= url('add.' . $data['type'], method: 'post'); ?>" accept-charset="UTF-8" method="post">
      <?= $container->csrf()->field(); ?>
      <textarea rows="5" minlength="6" placeholder="<?= __('app.markdown'); ?>..." name="content"></textarea>
      <fieldset>
        <input type="hidden" name="id" value="<?= $data['id']; ?>">
        <?= Html::sumbit(__('app.reply')); ?>
        <span id="cancel" class="text-sm inline ml5 gray"><?= __('app.cancel'); ?></span>
      </fieldset>
    </form>
  <?php else : ?>
    <div class="gray-600 text-sm">
      <?= __('app.no_auth'); ?>.
    </div>
  <?php endif; ?>
</div>