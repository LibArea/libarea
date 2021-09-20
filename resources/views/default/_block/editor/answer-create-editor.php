 <form id="add_answ" action="/answer/create" accept-charset="UTF-8" method="post">
   <?= csrf_field() ?>
   <div id="markdown-view">
     <textarea minlength="6" class="wmd-input h-150 w-95" rows="5" name="answer" id="wmd-input"></textarea>
   </div>
   <div class="clear">
     <input type="hidden" name="post_id" id="post_id" value="<?= $post_id; ?>">
     <input type="hidden" name="answer_id" id="answer_id" value="0">
     <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="button">
   </div>
 </form>
 <?= returnBlock('editor/config-editor', ['post_id' => $post_id, 'type' => 'answer', 'width100' => 'no']); ?>