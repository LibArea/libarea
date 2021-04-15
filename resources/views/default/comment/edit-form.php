<div class="cm_addentry"> 
    <?php if ($data['user_id'] > 0) : ?>
        <form id="add_comm" class="new_comment" action="/comment/edit" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
            <textarea rows="5" name="comment" id="comment"><?= $data['comment_content']; ?></textarea>
            <div> 
                <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
                <input type="hidden" name="comm_id" id="comm_id" value="<?= $data['comm_id']; ?>">
                <input type="submit" name="commit" value="Изменить" class="comment-post">
                <input id="cancel_cmm" type="button" value="Отмена">
            </div> 
        </form>
    <?php endif; ?>
</div>     