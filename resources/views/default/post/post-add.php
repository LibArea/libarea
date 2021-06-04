<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-100">

        <h1><?= $data['h1']; ?>
            <?php foreach ($space as $sp) { ?>
                <?php if($space_id == $sp['space_id']) { ?> 
                     / <span class="red"><?= $sp['space_name']; ?></span> 
                <?php } ?> 
            <?php } ?>
        </h1>
 
        <div class="box create">
            <form action="/post/create" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="boxline max-width">
                    <label for="post_title">Заголовок<sup class="red">*</sup></label>
                    <input id="title" class="add" minlength="6" maxlength="250" type="text" name="post_title" />
                    <div class="box_h">6 - 250 <?= lang('characters'); ?></div>
                </div>   
                <?php if($uid['trust_level'] == 5) { ?>
                    <div class="boxline max-width">
                        <label for="post_title">URL</label>
                        <input id="link" class="add-url" type="text" name="post_url" />
                        <input id="graburl" type="submit_url" name="submit_url" value="Извлечь" />
                        <br>
                    </div> <?php } ?>
                <div class="boxline max-width post">    
                    <div class="boxline">
                        <div class="input-images"></div>
                    </div>
                </div>
                <div class="boxline">
                    <?php include TEMPLATE_DIR . '/post/editor.php'; ?>
                </div>
                <div class="boxline"> 
                    <label for="post_content"><?= lang('Draft'); ?>?</label>
                    <input type="radio" name="post_draft" checked value="0"> <?= lang('No'); ?>
                    <input type="radio" name="post_draft" value="1" > <?= lang('Yes'); ?>
                </div> 
                <?php if($uid['trust_level'] > 0) { ?>
                    <div class="boxline"> 
                        <label for="post_content">Формат</label>
                        <input type="radio" name="post_type" checked value="0"> Обсуждение
                        <input type="radio" name="post_type" value="1" > Q&A
                    </div>
                    <div class="boxline"> 
                        <label for="post_content">Закрыть</label>
                        <input type="radio" name="closed" checked value="0"> <?= lang('No'); ?>
                        <input type="radio" name="closed" value="1" > <?= lang('Yes'); ?>
                    </div>  
                <?php } ?>
                    <div class="boxline"> 
                        <label for="post_content"><?= lang('Translation'); ?>?</label>
                        <input type="radio" name="translation" checked value="0"> <?= lang('No'); ?>
                        <input type="radio" name="translation" value="1" > <?= lang('Yes'); ?>
                    </div> 
                <?php if($uid['trust_level'] > 2) { ?>            
                    <div class="boxline">
                        <label for="post_content">Поднять</label>
                        <input type="radio" name="top" checked value="0"> <?= lang('No'); ?>
                        <input type="radio" name="top" value="1"> <?= lang('Yes'); ?>
                    </div>                      
                <?php } ?>
                <div class="boxline">
                            
         
                        <label for="post_content">Пространствa</label>
                        <select name="space_id">
                            <?php foreach ($space as $sp) { ?>
                                <option <?php if($space_id == $sp['space_id']) { ?>	selected<?php } ?> value="<?= $sp['space_id']; ?>">
                                    <?= $sp['space_name']; ?> 
                                </option>
                            <?php } ?>
                        </select>
                 
                </div>
                <input type="submit" name="submit" value="Написать" />
            </form>
            <br>
        </div>
    </main>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 