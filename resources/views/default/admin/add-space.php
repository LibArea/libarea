<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<main class="admin">
    <h1 class="top">
        <a href="/admin"><?= lang('Admin'); ?></a> / <a href="/admin/space"><?= lang('Space'); ?></a> / <span class="red"><?= $data['h1']; ?></span>
    </h1>
    
    <div class="telo space">
        <div class="box create">
            <form action="/admin/addspaceadmin" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">URL (slug) <sup class="red">*</sup></label>
                    <input class="add" type="text" value="" name="space_slug" />
                    <div class="box_h">Slug: 6 - 20 <?= lang('characters'); ?></div>
                    <br />
                </div>  
                <div class="boxline">
                    <label for="post_title"><?= lang('Title'); ?> <sup class="red">*</sup></label>
                    <input class="add" type="text" value="" name="space_name" />
                    <div class="box_h">Title: 6 - 25 <?= lang('characters'); ?></div>
                    <br />
                </div>
                <div class="boxline"> 
                    <label for="post_content"><?= lang('Publications'); ?> <sup class="red">*</sup></label>
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
                    <label for="post_content">Meta- <sup class="red">*</sup></label>
                    <textarea class="add" name="space_description"></textarea>
                    <div class="box_h">Description: 60 - 180 <?= lang('characters'); ?></div>
                    <br />
                </div>
                <div id="box" class="boxline">
                    <label for="post_content"><?= lang('Color'); ?></label>
                    <input type="color" value="<?= $space['space_color']; ?>" id="colorSpace">
                    <input type="hidden" name="color" value="" id="color">
                </div>
                <div class="boxline">
                    <label for="post_content"><?= lang('Text'); ?> <sup class="red">*</sup></label>
                    <textarea class="add" name="space_text"></textarea>
                    <div class="box_h">Title: 6 - 325 <?= lang('characters'); ?></div>
                    <br />
                </div>
                    <input type="submit" name="submit" value="<?= lang('Add'); ?>" />
            </form>
        </div>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?> 