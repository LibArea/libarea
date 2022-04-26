<div class="cm_addentry max-w780 mt10">
  <?php if (UserData::checkActiveUser()) : ?>
    <form action="<?= url('reply.edit'); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" name="content"><?= $data['content']; ?></textarea>
      <fieldset>
        <input type="hidden" name="item_id" value="<?= $data['item_id']; ?>">
        <input type="hidden" name="id" value="<?= $data['id']; ?>">
        <?= Html::sumbit(__('edit')); ?>
        <span id="cancel_comment" class="text-sm inline ml5 gray"><?= __('cancel'); ?></span>
      </fieldset>
      <div class="v-otsr"></div>
    </form>
  <?php endif; ?>
</div>