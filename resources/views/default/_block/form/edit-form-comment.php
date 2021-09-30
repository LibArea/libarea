<div class="cm_addentry">
  <?php if ($uid['user_id'] > 0) { ?>
    <form id="add_comm" class="new_comment" action="/comment/edit" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea rows="5" minlength="6" name="comment" id="comment"><?= $data['comment_content']; ?></textarea>
      <div class="boxline">
        <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
        <input type="hidden" name="comment_id" id="comment_id" value="<?= $data['comment_id']; ?>">
        <input type="submit" class="button block br-rd-5 white" name="commit" value="<?= lang('edit'); ?>">
        <input id="cancel_comment" class="cancel gray" type="button white mt5" value="<?= lang('cancel'); ?>">
      </div>
      <div class="v-otsr"></div>
    </form>
  <?php } ?>
</div>