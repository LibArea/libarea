<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <h1><?= $data['h1']; ?></h1>

    <div class="max-width space">
        <div class="box create">
            <form action="/space/addspace" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title">URL (slug)</label>
                    <input class="add" type="text" name="space_slug" />
                    <div class="box_h">На английском. Пример: <b>meta</b></div>
                    <br />
                </div>  
                <div class="boxline">
                    <label for="post_title">Название</label>
                    <input class="add" type="text" name="space_name" />
                    <div class="box_h">Одно, два слова</b></div>
                    <br />   
                </div>   
                <div class="boxline"> 
                    <label for="post_content">Публикации</label>
                    <input type="radio" name="permit" value="1"> Только я
                    <input type="radio" name="permit" value="2" > Все
                </div>                   
                    <input type="submit" name="submit" value="Добавить" />
            </form>
            <br>
            Тут правила на 1, 2 строки...
        </div>
    </div> 
</main>
<aside id="sidebar">  
    <div class="menu-info">
       Вы можете создать только одно пространство.
    </div> 
</aside>
<?php include TEMPLATE_DIR . '/footer.php'; ?>   