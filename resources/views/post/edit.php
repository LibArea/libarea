<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

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
                <?php if($uid['trust_level'] == 5) { ?>
                    <div class="boxline"> 
                        <label for="post_content">Закрыть</label>
                        <input type="radio" name="closed" <?php if($data['post_closed'] == 0) { ?>checked<?php } ?> value="0"> Нет
                        <input type="radio" name="closed" <?php if($data['post_closed'] == 1) { ?>checked<?php } ?> value="1" > Да
                    </div>  
                    <div class="boxline">
                        <label for="post_content">Поднять</label>
                        <input type="radio" name="top" <?php if($data['post_top'] == 0) { ?>checked<?php } ?> value="0"> Нет
                        <input type="radio" name="top" <?php if($data['post_top']== 1) { ?>checked<?php } ?> value="1"> Да
                    </div>                      
                <?php } ?>
                <div class="boxline">  
                    <label for="post_content">Пространство</label>
                       <b> <?= $data['space_name'] ?></b>
                      <br><small>  Изменение пространства пока не работают.</small> <br> 
                        
                     
                </div>
                <input type="hidden" name="post_id" id="post_id" value="<?= $data['id']; ?>">
                <input type="submit" name="submit" value="Изменить" />
            </form>
        </div>

    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 