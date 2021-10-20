<form id="add_answ" action="/answer/edit" accept-charset="UTF-8" method="post">
  <?= csrf_field() ?>
  <div class="redactor">
    <div id="markdown-view">
      <textarea name="answer" class="wmd-input h-150" id="wmd-input"><?= $content; ?></textarea>
    </div>
    <div id="wmd-preview" class="wmd-panel wmd-preview"></div>
  </div>
  <div class="boxline">
    <input type="hidden" name="post_id" id="post_id" value="<?= $post_id; ?>">
    <input type="hidden" name="answer_id" id="answer_id" value="<?= $answer_id; ?>">
    <input type="submit" class="button br-rd5 white mt5" name="answit" value="<?= lang('edit'); ?>">
  </div>
</form>
<?= includeTemplate('/_block/editor/config-editor', ['post_id' => $post_id, 'type' => 'answer', 'width100' => 'yes']); ?>