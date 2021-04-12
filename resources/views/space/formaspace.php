<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="left-ots">
         <span class="right"><a href="/s/<?= $space['space_slug']; ?>"><?= $space['space_name']; ?></a></span>
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
                        <label for="post_content">Meta- описание</label>
                        <textarea class="add" name="space_description"><?= $space['space_description']; ?></textarea><br />
                    </div>
                    <div class="boxline">
                        <label for="post_content">Цвет</label>
                        <input class="add" type="text" value="<?= $space['space_color']; ?>" name="space_color" />
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
                        <input type="submit" name="submit" value="Изменить" />
                </form>
            </div>
        </div> 
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>