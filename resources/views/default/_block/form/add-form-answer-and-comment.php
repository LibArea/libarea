<div class="cm_addentry max-w780 mt10">
  <?php if ($user['id'] > 0) { ?>
    <form id="add_comm" class="new_comment" action="<?= getUrlByName('content.create', ['type' => 'comment']); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" placeholder="<?= Translate::get('supports.markdown'); ?>..." name="comment"></textarea>
      <fieldset>
        <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
        <input type="hidden" name="answer_id" id="answer_id" value="<?= $data['answer_id']; ?>">
        <input type="hidden" name="comment_id" id="comment_id" value="<?= $data['comment_id']; ?>">
        <?= Html::sumbit(Translate::get('comment')); ?>
        <span id="cancel_comment" class="text-sm inline ml5 gray"><?= Translate::get('cancel'); ?></span>
      </fieldset>
    </form>
  <?php } else { ?>
    <textarea rows="5" disabled="disabled" placeholder="<?= Translate::get('no.auth.comm'); ?>."></textarea>
    <div>
      <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
      <input type="hidden" name="answer_id" id="answer_id" value="<?= $data['answer_id']; ?>">
      <?= Html::sumbit(Translate::get('comment')); ?>
      <span id="cancel_comment" class="text-sm inline ml5 gray"><?= Translate::get('cancel'); ?></span>
    </div>
  <?php } ?>
</div>