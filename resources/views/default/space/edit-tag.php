<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <h1><?= $data['h1']; ?></h1>
    <div class="telo space">
        <div class="box create">
            <form action="/space/tag/edit" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">Название</label>
                    <input class="add" type="text" value="<?= $tag['st_title']; ?>" name="st_title" />
                    <br />
                </div>
                    <div class="boxline">
                    <label for="post_content">Описание</label>
                     <input class="add" type="text" value="<?= $tag['st_description']; ?>" name="st_desc" />
                    <br />
                </div>
                <input type="hidden" name="space_id" id="space_id" value="<?= $tag['st_space_id']; ?>">
                <input type="hidden" name="tag_id" id="tag_id" value="<?= $tag['st_id']; ?>">
                <input type="submit" name="submit" value="Изменить" />
            </form>
        </div>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>