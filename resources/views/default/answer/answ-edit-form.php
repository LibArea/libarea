<div class="answ_addentry"> 
    <?php if ($data['user_id'] > 0) : ?>
        <form id="add_answ" class="new_answer" action="/answer/edit" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
            <textarea rows="5" name="answer" id="answer"><?= $data['answer_content']; ?></textarea>
            <div> 
                <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
                <input type="hidden" name="answ_id" id="answ_id" value="<?= $data['answ_id']; ?>">
                <input type="submit" name="answit" value="<?= lang('Edit'); ?>" class="answer-post">
                <input id="cancel_answ" type="button" value="<?= lang('Cancel'); ?>">
            </div> 
        </form>
    <?php endif; ?>
</div>     