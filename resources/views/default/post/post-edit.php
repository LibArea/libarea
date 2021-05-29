<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">

    <h1><?= $data['h1']; ?></h1>

     <div class="box edit-post">
        <form action="/post/editpost/<?= $post['post_id']; ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="boxline max-width">
                <label for="post_title">Заголовок<sup class="red">*</sup></label>
                <input class="add" max="250" type="text" value="<?= htmlspecialchars($post['post_title']); ?>" name="post_title" />
                <div class="box_h">6 - 250 <?= lang('characters'); ?></div>
            </div>   
            <?php if($uid['trust_level'] == 5) { ?>
                <div class="boxline max-width">
                    <label for="post_title">URL</label>
                    <?php if($post['post_url']) { ?>
                        <?= $post['post_url']; ?>
                    <?php } else { ?>
                        ...
                    <?php } ?>                    
                    <br />
                </div> 
            <?php } ?>
             
             <?php if($post['post_content_img']) { ?> 
                <div class="img-post-edit">
                    <img class="img-post" alt="<?= $post['post_title']; ?>" src="/uploads/posts/<?= $post['post_content_img']; ?>">
                    <input type="hidden" name="content_img" value="<?= $post['post_content_img']; ?>">
                </div>    
            <?php } ?>

            <?php if($post['post_thumb_img']) { ?> 
                <img class="thumb" alt="<?= $post['post_title']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
            <?php } ?> 
             
            <div class="boxline max-width post">    
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
                <div class="boxline"> 
                    <label for="post_content"><?= lang('Translation'); ?>?</label>
                    <input type="radio" name="translation" <?php if($post['post_translation'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                    <input type="radio" name="translation" <?php if($post['post_translation'] == 1) { ?>checked<?php } ?> value="1" > <?= lang('Yes'); ?>
                </div>    
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
            
            <?php if($uid['trust_level'] > 4) { ?> 
                <div class="boxline">  
                    <label for="post_content">Автор</label>
                    <select name="post_user_new" id='selUser'>
                        <option value="<?= $user['id']; ?>"><?= $user['login']; ?></option>
                    </select>
                    <script nonce="<?= $_SERVER['nonce']; ?>">
                        $(document).ready(function(){
                            $("#selUser").select2({
                                ajax: { 
                                    url: "/search/users",
                                    type: "post",
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                    return {
                                      searchTerm: params.term 
                                    };
                                    },
                                    processResults: function (response) {
                                     return {
                                        results: response
                                     };
                                    },
                                    cache: true
                                }
                            });
                        });
                    </script>
                </div>    
            <?php } ?>
            
            <div class="boxline">
                <br>
                <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                <input type="submit" name="submit" value="<?= lang('Edit'); ?>" />
            </div>
        </form>
        <br>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 