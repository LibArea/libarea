<div class="cm_addentry max-w780 mt10">
  <?php if ($user['id'] > 0) : ?>
    <form id="add_comm" class="new_comment" action="<?= getUrlByName('content.change', ['type' => 'comment']); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" name="comment" id="comment"><?= $data['comment_content']; ?></textarea>
      <fieldset>
        <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
        <input type="hidden" name="comment_id" id="comment_id" value="<?= $data['comment_id']; ?>">
        <?= Html::sumbit(__('edit')); ?>
        <span id="cancel_comment" class="text-sm inline ml5 gray"><?= __('cancel'); ?></span>
      </fieldset>
      <div class="v-otsr"></div>
    </form>
  <?php endif; ?>
</div>