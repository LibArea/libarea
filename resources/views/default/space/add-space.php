<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
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
                    <label for="post_title"><?= lang('Title'); ?></label>
                    <input class="add" type="text" name="space_name" />
                    <div class="box_h">Одно, два слова</div>
                    <br />   
                </div>   
                <div class="boxline"> 
                    <label for="post_content"><?= lang('Publications'); ?></label>
                    <input type="radio" name="permit" checked value="0"> <?= lang('All'); ?>
                    <input type="radio" name="permit" value="1" > <?= lang('Just me'); ?>
                    <div class="box_h">Кто сможет размещать посты</div>
                    <br />
                </div> 
                <div class="boxline"> 
                    <label for="post_content">Показывать</label>
                    <input type="radio" name="feed" checked value="0"> <?= lang('Yes'); ?>
                    <input type="radio" name="feed" value="1" > <?= lang('No'); ?>
                    <div class="box_h">Если нет, то посты не будут видны в ленте (на главной)</b></div>
                    <br />  
                </div>                 
                    <input type="submit" name="submit" value="<?= lang('Add'); ?>" />
            </form>
            <br>
            Вы можете добавить пространств: <b><?= $num_add_space; ?></b>
        </div>
    </div> 
</main>
<aside class="sidebar">  
 
</aside>
<?php include TEMPLATE_DIR . '/footer.php'; ?>   