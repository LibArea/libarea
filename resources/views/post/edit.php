<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <link rel="stylesheet" href="/css/select2.css">
        <script src="/js/select2.min.js"></script>
        <script src="/js/post.js"></script>

        <h2><?php echo $data['title']; ?></h2>

         <div class="box create">
            <form action="/posts/edit/<?php echo $data['id']; ?>" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">Заголовок</label>
                    <input class="add" type="text" value="<?php echo htmlspecialchars($data['title_post']); ?>" name="post_title" /><br />
                </div>        
                <div class="boxline">
                    <label for="post_content">Текст</label>
                    <textarea name="post_content"><?php echo $data['content']; ?></textarea><br />
                </div>
                
                <div class="boxline">  
                    <label for="post_content">Теги</label>
                    <select name="tag" class="js-example-placeholder-multiple js-states form-control" multiple="multiple">
                        <?php foreach ($data['tag'] as  $tag ) { ?>
                            <option selected="selected" name="tag" value="<?php echo $tag['tags_id']; ?>">
                                <?php echo $tag['tags_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                      <br>  Теги не работают, показан изначальный выбор.<br> 
                        
                        1- cms,  
                        2 - вопросы, 
                        3 - флуд 
                </div>
                
                <input type="submit" name="submit" value="Изменить" />
            </form>
        </div>

    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 