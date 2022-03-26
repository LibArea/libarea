<div class="cm_addentry max-w780 mt10">
  <?php if ($user['id'] > 0) { ?>
    <form action="<?= getUrlByName('reply.create'); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" placeholder="<?= Translate::get('supports.markdown'); ?>..." name="content"></textarea>
      <fieldset>
        <input type="hidden" name="id" value="<?= $data['id']; ?>">
        <input type="hidden" name="pid" value="<?= $data['pid']; ?>">
        <?= Html::sumbit(Translate::get('comment')); ?>
        <span id="cancel_comment" class="text-sm inline ml5 gray"><?= Translate::get('cancel'); ?></span>
      </fieldset>
    </form>
  <?php } else { ?>
    <textarea rows="5" disabled="disabled"><?= Translate::get('no.auth.comm'); ?>...</textarea>
    <div>
      <?= sumbit(Translate::get('comment')); ?>
      <span id="cancel_comment" class="text-sm inline ml5 gray"><?= Translate::get('cancel'); ?></span>
    </div>
  <?php } ?>
</div>