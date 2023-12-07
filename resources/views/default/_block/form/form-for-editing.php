<div class="max-w780 mt10">
  <?php if (UserData::checkActiveUser()) : ?>
    <form action="<?= url('content.change', ['type' => $data['type']]); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" name="content"><?= $data['content']; ?></textarea>
      <fieldset>
        <input type="hidden" name="id" value="<?= $data['id']; ?>">
        <?= Html::sumbit(__('app.edit')); ?>
        <span id="cancel" class="text-sm inline ml5 gray"><?= __('app.cancel'); ?></span>
      </fieldset>
    </form>
  <?php endif; ?>
</div>