<div class="cm_addentry"> 
    <?php if (!$uid['id'] > 0) { ?>
        <textarea rows="5" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>." name="content" id="content"></textarea>
            <div> 
                <input type="hidden" name="post_id" id="post_id" value="<?php echo $data['post_id']; ?>">
                <input type="hidden" name="comm_id" id="comm_id" value="<?php echo $data['comm_id']; ?>">
                <input type="submit" name="commit" value="<?= lang('Comment'); ?>" class="comment-post">
                <input id="cancel_cmm"  type="button" value="<?= lang('Cancel'); ?>">
            </div> 
    <?php } else { ?>
        <form id="add_comm" class="new_comment" action="/comment/add" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
            <textarea rows="5" placeholder="<?= lang('write-something'); ?>..." name="comment" id="comment"></textarea>
            <div> 
                <input type="hidden" name="post_id" id="post_id" value="<?php echo $data['post_id']; ?>">
                <input type="hidden" name="comm_id" id="comm_id" value="<?php echo $data['comm_id']; ?>">
                <input type="submit" name="commit" value="<?= lang('Comment'); ?>" class="comment-post">
                <input id="cancel_cmm"  type="button" value="<?= lang('Cancel'); ?>">
            </div> 
        </form>
    <?php } ?>
</div>     