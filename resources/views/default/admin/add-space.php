<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<main class="admin">
    <h1 class="top">
        <a href="/admin"><?= lang('Admin'); ?></a> / <a href="/admin/space"><?= lang('Space'); ?></a> / <span class="red"><?php echo $data['h1']; ?></span>
    </h1>
    
    <div class="telo space">
        <div class="box create">
            <form action="/admin/addspaceadmin" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">URL (slug)</label>
                    <input class="add" type="text" value="" name="space_slug" />
                    <br />
                </div>  
                <div class="boxline">
                    <label for="post_title"><?= lang('Title'); ?></label>
                    <input class="add" type="text" value="" name="space_name" />
                    <br />
                </div>
                <div class="boxline"> 
                    <label for="post_content"><?= lang('Publications'); ?></label>
                    <input type="radio" name="permit" value="1"> <?= lang('Just me'); ?>
                    <input type="radio" name="permit" value="2" > <?= lang('All'); ?>
                </div>  
                <div class="boxline"> 
                    <label for="post_content">Показывать</label>
                    <input type="radio" name="feed" value="0"> <?= lang('Yes'); ?>
                    <input type="radio" name="feed" value="1" > <?= lang('No'); ?>
                    <div class="box_h">Если нет, то посты не будут видны в ленте (на главной)</b></div>
                    <br />  
                </div> 
                <div class="boxline">
                    <label for="post_content">Meta-</label>
                    <textarea class="add" name="space_description"></textarea><br />
                </div>
                <!-- Possible: https://developer.mozilla.org/ru/docs/Web/HTML/Applying_color -->
                <div class="boxline">
                    <label for="post_content"><?= lang('Color'); ?></label>
                    <?php include TEMPLATE_DIR . '/admin/space-color-box.php'; ?>
                </div>
                <div class="boxline">
                    <label for="post_content"><?= lang('Text'); ?></label>
                    <textarea class="add" name="space_text"></textarea><br />
                </div>
                    <input type="submit" name="submit" value="<?= lang('Add'); ?>" />
            </form>
        </div>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?> 