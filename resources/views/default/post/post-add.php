<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">

    <h1><?php echo $data['h1']; ?></h1>

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
                    <input id="link" class="add-url" type="text" name="post_url" />
                    <input id="graburl" type="submit_url" name="submit_url" value="Извлечь" />
                    <br>
                </div> <?php } ?>
                
            <div class="boxline">
                <?php include TEMPLATE_DIR . '/post/editor.php'; ?>
            </div>
            
            <?php if($uid['trust_level'] > 0) { ?>
                <div class="boxline"> 
                    <label for="post_content">Формат</label>
                    <input type="radio" name="post_type" checked value="0"> Обсуждение
                    <input type="radio" name="post_type" value="1" > Q&A
                </div> 
                <div class="boxline"> 
                    <label for="post_content">Закрыть</label>
                    <input type="radio" name="closed" value="0"> <?= lang('No'); ?>
                    <input type="radio" name="closed" value="1" > <?= lang('Yes'); ?>
                </div>  
            <?php } ?>
            <?php if($uid['trust_level'] > 2) { ?>            
                <div class="boxline">
                    <label for="post_content">Поднять</label>
                    <input type="radio" name="top" value="0"> <?= lang('No'); ?>
                    <input type="radio" name="top" value="1"> <?= lang('Yes'); ?>
                </div>                      
            <?php } ?>
            <div class="boxline">
                <label for="post_content">Пространствa</label>
                <select name="space_id">
                    <?php foreach ($space as $sp) { ?>
                        <option value="<?= $sp['space_id']; ?>"><?= $sp['space_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <input type="submit" name="submit" value="Написать" />
        </form>
        <br>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 