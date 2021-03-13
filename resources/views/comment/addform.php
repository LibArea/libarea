<div class="cm_addentry"> 
    <?php if (!$data['user_id'] > 0) : ?>
        <textarea rows="5" disabled="disabled" placeholder="Вы должны войти в систему, чтобы оставить комментарий." name="content" id="content"></textarea>
        <div class="submit_cmm"> 
            <input id="submit_cmm" type="submit" name="commit" value="Комментарий" class="comment-post" disabled="disabled">
            <input id="cancel_cmm" type="button" value="Отмена">
        </div> 
    <?php else : ?>
        <form id="add_comm" class="new_comment" action="/comment/add" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
            <textarea rows="5" placeholder="Напишите, что нибудь..." name="comment" id="comment"></textarea>
            <div> 
                <input type="hidden" name="post_id" id="post_id" value="<?php echo $data['post_id']; ?>">
                <input type="hidden" name="comm_id" id="comm_id" value="<?php echo $data['comm_id']; ?>">
                <input type="submit" name="commit" value="Комментарий" class="comment-post">
                <input id="cancel_cmm" type="button" value="Отмена">
            </div> 
        </form>
    <?php endif; ?>
    
</div>     