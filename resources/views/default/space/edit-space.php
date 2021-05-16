<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
 
    <h1><?= $data['h1']; ?></h1>
    <div class="telo space">
        <div class="box create">
            <form action="/space/editspace" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">URL<sup class="red">*</sup></label>
                    <input class="add" type="text" value="<?= $space['space_slug']; ?>" name="space_slug" />
                    <br />
                </div>  
                <div class="boxline">
                    <label for="post_title">Название<sup class="red">*</sup></label>
                    <input class="add" type="text" value="<?= $space['space_name']; ?>" name="space_name" />
                    <div class="box_h">4 - 18 <?= lang('characters'); ?></div>
                </div>
                <div class="boxline"> 
                    <label for="post_content">Публикации<sup class="red">*</sup></label>
                    <input type="radio" name="permit" <?php if($space['space_permit_users'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('All'); ?>
                    <input type="radio" name="permit" <?php if($space['space_permit_users'] == 1) { ?>checked<?php } ?> value="1" > <?= lang('Just me'); ?>
                    <div class="box_h">Кто сможет размещать посты</div>
                    <br />
                </div>  
                <div class="boxline"> 
                    <label for="post_content">Показывать<sup class="red">*</sup></label>
                    <input type="radio" name="feed" <?php if($space['space_feed'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('Yes'); ?>
                    <input type="radio" name="feed" <?php if($space['space_feed'] == 1) { ?>checked<?php } ?> value="1" > <?= lang('No'); ?>
                    <div class="box_h">Если нет, то посты не будут видны в ленте (на главной)</b></div>
                    <br />  
                </div> 
                <div class="boxline">
                    <label for="post_content">Meta-<sup class="red">*</sup></label>
                    <textarea class="add" name="space_description"><?= $space['space_description']; ?></textarea>
                    <div class="box_h">Description: 60 - 180 <?= lang('characters'); ?></div>
                    <br />
                </div>
                <div id="box" class="boxline">
                    <label for="post_content"><?= lang('Color'); ?></label>
                    <input type="color" value="<?= $space['space_color']; ?>" id="colorSpace">
                    <input type="hidden" name="color" value="" id="color">
                </div>
                <div class="boxline">
                    <br />
                    <label for="post_content"><?= lang('Text'); ?></label>
                    <br>
                    <textarea class="add" id="answer_100" name="space_text"><?= $space['space_text']; ?></textarea>
                </div>
                <div class="box setting space">
                    <img class="ava" src="/uploads/space/<?= $space['space_img']; ?>">
                    <div class="box-form-img"> 
                        <div class="boxline">
                            <div class="input-images"></div>
                        </div>
                    </div> 
                    <input type="hidden" name="space_id" id="space_id" value="<?= $space['space_id']; ?>">
                    <div class="clear"> 
                        <p><?= lang('select-file-up'); ?>: 120x120px (jpg, jpeg, png)</p>
                        <input type="submit" name="submit" value="<?= lang('Edit'); ?>" />
                    </div> 
                </div>                
            </form>
        </div>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/_block/space-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>