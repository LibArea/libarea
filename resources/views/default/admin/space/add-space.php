<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / 
                    <a href="/admin/spaces"><?= lang('Spaces'); ?></a> / 
                    <span class="red"><?= $data['h1']; ?></span>
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
                                <label for="post_content"><?= lang('Long'); ?><sup class="red">*</sup></label>
                                <input class="add"  type="text" name="space_short_text" value="">
                                <div class="box_h">Длинное название от 20 - 250 <?= lang('characters'); ?></div>
                                <br />
                            </div>
                            <div class="boxline"> 
                                <label for="post_content"><?= lang('Publications'); ?> <sup class="red">*</sup></label>
                                <input type="radio" name="permit" checked value="2" > <?= lang('All'); ?>
                                <input type="radio" name="permit" value="1"> <?= lang('Just me'); ?>
                            </div>  
                            <div class="boxline"> 
                                <label for="post_content"><?= lang('Show'); ?></label>
                                <input type="radio" name="feed" checked value="0"> <?= lang('Yes'); ?>
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
                            <div class="boxline">
                                <label for="post_content">Sidebar<sup class="red">*</sup></label>
                                <textarea class="add" name="space_text"></textarea>
                                <div class="box_h">Title: 6 - 325 <?= lang('characters'); ?></div>
                                <br />
                            </div>
                                <input type="submit" name="submit"  class="button" value="<?= lang('Add'); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 