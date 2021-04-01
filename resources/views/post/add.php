<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h2><?php echo $data['h1']; ?></h2>

        <div class="box create">
            <form action="/post/create" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">Заголовок</label>
                    <input id="title" class="add" type="text" name="post_title" /><br />
                </div>   
                <?php if($uid['trust_level'] == 5) { ?>
                    <div class="boxline">
                        <label for="post_title">URL</label>
                        <span class="w-50">
                            <input id="link" class="add" type="url" name="post_url" />
                        </span>
                        <span class="w-33">
                            <input id="graburl" type="submit_url" name="submit_url" value="Извлечь" />
                        </span>
                        <br>
                    </div>
                <?php } ?>
                <div class="boxline">
                    <label for="post_content">Текст</label>
                    <textarea name="post_content"></textarea> <br />
                </div>
                <div class="boxline">
                    <label for="post_content">Пространство</label>
                    <input type="radio" name="space" value="1" > cms
                    <input type="radio" name="space" value="2" > вопросы
                    <input type="radio" name="space" value="3" > флуд
                </div>
         
                Избегайте спама...
                <br><br>
                <input type="submit" name="submit" value="Написать" />
            </form>
        </div>
        
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 