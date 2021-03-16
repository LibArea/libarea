<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h2><?php echo $data['title']; ?></h2>

        <div class="box create">
            <form action="/post/create" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">Заголовок</label>
                    <input class="add" type="text" name="post_title" /><br />
                </div>        
                <div class="boxline">
                    <label for="post_content">Текст</label>
                    <textarea name="post_content"></textarea><br />
                </div>
                
                <div class="boxline">
                    <label for="post_content">Пространство</label>
                    <input type="radio" name="space" value="1" > cms
                    <input type="radio" name="space" value="2" > вопросы
                    <input type="radio" name="space" value="3" > флуд
                </div>
                
                
                <input type="submit" name="submit" value="Написать" />
            </form>
        </div>
        
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 