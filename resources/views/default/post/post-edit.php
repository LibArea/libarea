<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">

    <h1><?= $data['h1']; ?></h1>

     <div class="box setting post">
        <form action="/post/editpost/<?= $post['post_id']; ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="boxline">
                <label for="post_title">Заголовок</label>
                <input class="add" type="text" value="<?= htmlspecialchars($post['post_title']); ?>" name="post_title" />
                <br />
            </div>   
            <?php if($uid['trust_level'] == 5) { ?>
                <div class="boxline">
                    <label for="post_title">URL</label>
                    <input class="add-url" type="text" value="<?= $post['post_url']; ?>" name="post_url" />
                    <input id="graburl" type="submit_url" name="submit_url" value="Извлечь" />
                    <br />
                </div> 
            <?php } ?>
             
             <?php if($post['post_content_img']) { ?> 
                <div class="img-post-edit">
                    <img class="img-post" alt="<?= $post['post_title']; ?>" src="/uploads/post/<?= $post['post_content_img']; ?>">
                    <input type="hidden" name="content_img" value="<?= $post['post_content_img']; ?>">
                </div>    
            <?php } ?>

            <?php if($post['post_thumb_img']) { ?> 
                <img class="thumb" alt="<?= $post['post_url']; ?>" src="/uploads/post/thumbnails/<?= $post['post_thumb_img']; ?>">
            <?php } ?> 
             
            <div class="boxline post">    
                <div class="boxline">
                    <div class="input-images"></div>
                </div>
            </div>
             
            <div class="boxline">
                <?php include TEMPLATE_DIR . '/post/editor.php'; ?> 
            </div>
            <?php if($post['post_draft'] == 1) { ?>
                <div class="boxline"> 
                    <label for="post_content"><?= lang('Draft'); ?>?</label>
                    <input type="radio" name="post_draft" <?php if($post['post_draft'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                    <input type="radio" name="post_draft" <?php if($post['post_draft'] == 1) { ?>checked<?php } ?> value="1" > <?= lang('Yes'); ?>
                </div> 
            <?php } ?>
            <?php if($uid['trust_level'] > 0) { ?>
                <div class="boxline"> 
                    <label for="post_content">Формат</label>
                    <input type="radio" name="post_type" <?php if($post['post_type'] == 0) { ?>checked<?php } ?> value="0"> Обсуждение
                    <input type="radio" name="post_type" <?php if($post['post_type'] == 1) { ?>checked<?php } ?> value="1" > Q&A
                </div> 
                <div class="boxline"> 
                    <label for="post_content">Закрыть</label>
                    <input type="radio" name="closed" <?php if($post['post_closed'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                    <input type="radio" name="closed" <?php if($post['post_closed'] == 1) { ?>checked<?php } ?> value="1" > <?= lang('Yes'); ?>
                </div>  
            <?php } ?>
            <?php if($uid['trust_level'] > 2) { ?>            
                <div class="boxline">
                    <label for="post_content">Поднять</label>
                    <input type="radio" name="top" <?php if($post['post_top'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                    <input type="radio" name="top" <?php if($post['post_top']== 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
                </div>                      
            <?php } ?>
            <div class="boxline">  
                <label for="post_content">Пространство</label>
                <select name="space_id">
                    <?php foreach ($space as $sp) { ?>
                        <option <?php if($post['space_id'] == $sp['space_id']) { ?> selected<?php } ?>   value="<?= $sp['space_id']; ?>"> <?= $sp['space_name']; ?>
                        </option>
                    <?php } ?>
                </select>
                <br> 
            </div>
            
            <?php if($tags) { ?>
                <div class="boxline">  
                    <label for="post_content">Метки</label>
                    <?php foreach ($tags as $tag) { ?>
                        <input type="radio" name="tag_id" value="<?= $tag['st_id']; ?>"<?php if($post['post_tag_id'] == $tag['st_id']) { ?> checked<?php } ?>><?= $tag['st_title']; ?>
                    <?php } ?>
                    <br> 
                </div>
            <?php } ?>
            <?php if($post['post_draft'] == 1) { ?>
                <input type="hidden" name="draft" id="draft" value="1">
            <?php } ?>
            <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
            <input type="submit" name="submit" value="<?= lang('Edit'); ?>" />
        </form>
        <br>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 