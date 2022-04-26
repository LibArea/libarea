<div class="cm_addentry max-w780 mt10">
  <?php if (UserData::checkActiveUser()) : ?>
    <form action="<?= url('reply.create'); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" placeholder="<?= __('supports.markdown'); ?>..." name="content"></textarea>
      <fieldset>
        <input type="hidden" name="id" value="<?= $data['id']; ?>">
        <input type="hidden" name="item_id" value="<?= $data['item_id']; ?>">
        <?= Html::sumbit(__('comment')); ?>
        <span id="cancel_comment" class="text-sm inline ml5 gray"><?= __('cancel'); ?></span>
      </fieldset>
    </form>
  <?php else : ?>
    <textarea rows="5" disabled="disabled"><?= __('no.auth.comm'); ?>...</textarea>
    <div>
      <?= Html::sumbit(__('comment')); ?>
      <span id="cancel_comment" class="text-sm inline ml5 gray"><?= __('cancel'); ?></span>
    </div>
  <?php endif; ?>
</div>