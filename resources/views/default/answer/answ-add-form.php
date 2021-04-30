<div class="answ_addentry"> 
    <?php if (!$uid['id'] > 0) { ?>
        <textarea rows="5" disabled="disabled" placeholder="<?= lang('no-auth-answ'); ?>." name="answer" id="answer"></textarea>
            <div> 
                <input type="hidden" name="post_id" id="post_id" value="<?php echo $data['post_id']; ?>">
                <input type="hidden" name="answ_id" id="answ_id" value="<?php echo $data['answ_id']; ?>">
                <input type="submit" name="answit" value="<?= lang('Кeply'); ?>" class="answer-post">
                <input id="cancel_cmm"  type="button" value="<?= lang('Cancel'); ?>">
            </div> 
    <?php } else { ?>
        <form id="add_answ" class="new_answer" action="/answer/add" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
            <textarea rows="5" placeholder="<?= lang('write-something'); ?>..." name="answer" id="answer"></textarea>
            <div> 
                <input type="hidden" name="post_id" id="post_id" value="<?php echo $data['post_id']; ?>">
                <input type="hidden" name="answ_id" id="comm_id" value="<?php echo $data['answ_id']; ?>">
                <input type="submit" name="answer" value="<?= lang('Кeply'); ?>" class="answer-post">
                <input id="cancel_answ"  type="button" value="<?= lang('Cancel'); ?>">
            </div> 
        </form>
    <?php } ?>
</div>     