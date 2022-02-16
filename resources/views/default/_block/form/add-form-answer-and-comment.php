<div class="cm_addentry max-w780 mt10">
  <?php if ($user['id'] > 0) { ?>
    <form id="add_comm" class="new_comment" action="<?= getUrlByName('comment.create'); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" placeholder="<?= Translate::get('write-something'); ?>..." name="comment" id="comment"></textarea>
      <fieldset>
        <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
        <input type="hidden" name="answer_id" id="answer_id" value="<?= $data['answer_id']; ?>">
        <input type="hidden" name="comment_id" id="comment_id" value="<?= $data['comment_id']; ?>">
        <?= sumbit(Translate::get('comment')); ?>
        <span id="cancel_comment" class="text-sm inline ml5 gray"><?= Translate::get('cancel'); ?></span>
      </fieldset>
    </form>
  <?php } else { ?>
    <textarea rows="5" disabled="disabled" class="bg-gray-000" placeholder="<?= Translate::get('no-auth-comm'); ?>." name="content" id="content"></textarea>
    <div>
      <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
      <input type="hidden" name="answer_id" id="answer_id" value="<?= $data['answer_id']; ?>">
      <?= sumbit(Translate::get('comment')); ?>
      <span id="cancel_comment" class="text-sm inline ml5 gray"><?= Translate::get('cancel'); ?></span>
    </div>
  <?php } ?>
</div>