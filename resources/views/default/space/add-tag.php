<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <h1><?= $data['h1']; ?></h1>
    <div class="telo space">
        <div class="box create">
            <form action="/space/tag/add" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title"><?= lang('Title'); ?></label>
                    <input class="add" type="text" value="" name="st_title" />
                    <div class="box_h">От 4 до 20 знаков</div>
                </div>
                    <div class="boxline">
                    <label for="post_content"><?= lang('Description'); ?></label>
                    <input class="add" type="text" value="" name="st_desc" />
                    <div class="box_h">От 20 до 180 знаков</div>
                </div>
                <input type="hidden" name="space_id" id="space_id" value="<?= $space['space_id']; ?>">
                <input type="submit" name="submit" value="<?= lang('Add'); ?>" />
            </form>
        </div>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>