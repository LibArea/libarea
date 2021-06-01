<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
        <h1><?= $data['h1']; ?></h1>

        <div class="max-width space">
            <div class="box create">
                <form action="/space/addspace" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="boxline">
                        <label for="post_title">URL (slug)<sup class="red">*</sup></label>
                        <input class="add" minlength="3" type="text" name="space_slug" />
                        <div class="box_h">На английском. Пример: <b>meta</b></div>
                        <br />
                    </div>  
                    <div class="boxline">
                        <label for="post_title"><?= lang('Title'); ?><sup class="red">*</sup></label>
                        <input class="add" minlength="6" type="text" name="space_name" />
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
                        <label for="post_content"><?= lang('Show'); ?></label>
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
    <aside>  
       Правила... в стадии разработки... 
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>   