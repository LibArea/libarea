<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <link rel="stylesheet" href="/css/select2.css">
        <script src="/js/select2.min.js"></script>
        <script src="/js/post.js"></script>

        <h2><?= $data['title']; ?></h2>

         <div class="box create">
            <form action="/post/editpost/<?= $data['id']; ?>" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">Заголовок</label>
                    <input class="add" type="text" value="<?= $data['title_post']; ?>" name="post_title" /><br />
                </div>        
                <div class="boxline">
                    <label for="post_content">Текст</label>
                    <textarea name="post_content"><?= $data['content']; ?></textarea><br />
                </div>
                
                <div class="boxline">  
                    <label for="post_content">Теги</label>
                    <select name="tag" class="js-example-placeholder-multiple js-states form-control" multiple="multiple">
                        <?php foreach ($data['tag'] as  $tag ) { ?>
                            <option selected="selected" name="tag" value="<?= $tag['tags_id']; ?>">
                                <?= $tag['tags_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                      <br>  Теги не работают, показан изначальный выбор.<br> 
                        
                        1- cms,  
                        2 - вопросы, 
                        3 - флуд 
                </div>
                <input type="hidden" name="post_id" id="post_id" value="<?= $data['id']; ?>">
                <input type="submit" name="submit" value="Изменить" />
            </form>
        </div>

    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 