<div class="add-flow"> 
    <?php if (!$uid['id'] > 0) : ?>
        <div id="add_flow"> 
            <textarea rows="1" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>." id="flow"></textarea>
            <div class="add-flow-butt"> 
                <input type="button" value="<?= lang('Reply'); ?>">
            </div> 
        </div> 
    <?php else : ?>
        <form id="add_flow" action="/flow/add" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
            <textarea rows="1" placeholder="<?= lang('write-something'); ?>..." name="flow" id="flow"></textarea>
            <div class="add-flow-butt"> 
                <input type="submit" name="commit" value="<?= lang('Reply'); ?>" class="comment-post">
            </div> 
        </form>
    <?php endif; ?>
</div>     