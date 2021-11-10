<?php if ($user_id > 0) { ?>
  <?php if ($data['post_draft'] == 0 && $data['post_closed'] == 0) { ?>
    <form id="add_answ" action="/answer/create" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <div id="markdown-view" class="mb10">
        <textarea minlength="6" class="wmd-input h-150 w-95" rows="5" name="answer" id="wmd-input"></textarea>
      </div>
      <div class="clear pt5">
        <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
        <input type="hidden" name="answer_id" id="answer_id" value="0">
        <?= sumbit(Translate::get('reply')); ?>
      </div>
    </form>
    <?= includeTemplate('/_block/editor/config-editor', ['post_id' => $data['post_id'], 'lang' => $lang, 'type' => 'answer', 'width100' => 'no']); ?>
  <?php } ?>
<?php } ?>

 