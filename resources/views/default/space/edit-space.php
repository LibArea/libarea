<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
 
    <h1><?= $data['h1']; ?></h1>
    <div class="telo space">
        <div class="box create">
            <form action="/space/editspace" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">URL</label>
                    <input class="add" type="text" value="<?= $space['space_slug']; ?>" name="space_slug" />
                    <br />
                </div>  
                <div class="boxline">
                    <label for="post_title">Название</label>
                    <input class="add" type="text" value="<?= $space['space_name']; ?>" name="space_name" />
                    <br />
                </div>
                <div class="boxline"> 
                    <label for="post_content">Публикации</label>
                    <input type="radio" name="permit" <?php if($space['space_permit_users'] == 1) { ?>checked<?php } ?> value="1"> Только я
                    <input type="radio" name="permit" <?php if($space['space_permit_users'] == 2) { ?>checked<?php } ?> value="2" > Все
                </div>                  
                <div class="boxline">
                    <label for="post_content">Meta-</label>
                    <textarea class="add" name="space_description"><?= $space['space_description']; ?></textarea><br />
                </div>
                <div class="boxline">
                    <label for="post_content">Цвет</label>
                    <!-- Можно: https://developer.mozilla.org/ru/docs/Web/HTML/Applying_color -->
                    <?php include TEMPLATE_DIR . '/space/space-color-box.php'; ?>
                </div>
                <div class="boxline">
                    <label for="post_content">Текст</label>
                    <textarea class="add" name="space_text"><?= $space['space_text']; ?></textarea><br />
                </div>
                <div class="boxline">
                <label for="space_img">
                    <img width="60" src="/uploads/space/<?= $space['space_img']; ?>">
                </label>
                    <input type="file" name="image" accept="image/*"/>
                    <p>Выберите файл для загрузки 120x120px (jpg, jpeg, png)</p>
                </div>
                    <input type="hidden" name="space_id" id="space_id" value="<?= $space['space_id']; ?>">
                    <input type="submit" name="submit" value="<?= lang('Edit'); ?>" />
            </form>
        </div>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/_block/space-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>