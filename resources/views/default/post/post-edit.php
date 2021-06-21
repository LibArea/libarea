<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-100">
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                 <div class="box edit-post">
                    <form action="/post/editpost/<?= $post['post_id']; ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="form">
                            <div class="boxline">
                                <label class="form-label" for="post_title">Заголовок<sup class="red">*</sup></label>
                                <input class="form-input" minlength="6" maxlength="250" type="text" value="<?= htmlspecialchars($post['post_title']); ?>" name="post_title" />
                                <div class="box_h">6 - 250 <?= lang('characters'); ?></div>
                            </div>   
                            <?php if($uid['trust_level'] == 5) { ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_title">URL</label>
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
                                    
                                    <a class="img-remove" href="/post/img/<?= $post['post_id']; ?>/remove">
                                        <small><?= lang('Remove'); ?></small>
                                    </a> 
                                </div>    
                            <?php } ?>

                            <?php if($post['post_thumb_img']) { ?> 
                                <img class="thumb" alt="<?= $post['post_title']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
                            <?php } ?> 
                             
                            <div class="boxline post">    
                                <div class="boxline">
                                    <div class="input-images"></div>
                                </div>
                            </div>
                        </div> 
                        <div class="boxline">
                            <?php include TEMPLATE_DIR . '/post/editor.php'; ?> 
                        </div>
                        <div class="form">
                            <?php if($post['post_draft'] == 1) { ?>
                                <div class="boxline"> 
                                    <label class="form-label" for="post_content"><?= lang('Draft'); ?>?</label>
                                    <input type="radio" name="post_draft" <?php if($post['post_draft'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="post_draft" <?php if($post['post_draft'] == 1) { ?>checked<?php } ?> value="1" > <?= lang('Yes'); ?>
                                </div> 
                            <?php } ?>
                            <?php if($uid['trust_level'] > 0) { ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('For'); ?> TL</label>
                                    <select class="form-input" name="post_tl">
                                        <?php for($i=0; $i<=$uid['trust_level']; $i++) {  ?>
                                            <option <?php if($post['post_tl'] == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="boxline"> 
                                    <label class="form-label" for="post_content"><?= lang('Format'); ?></label>
                                    <input type="radio" name="post_type" <?php if($post['post_type'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('Discussion'); ?>
                                    <input type="radio" name="post_type" <?php if($post['post_type'] == 1) { ?>checked<?php } ?> value="1" > Q&A
                                </div> 
                                <div class="boxline"> 
                                    <label class="form-label" for="post_content"><?= lang('To close'); ?>?</label>
                                    <input type="radio" name="closed" <?php if($post['post_closed'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="closed" <?php if($post['post_closed'] == 1) { ?>checked<?php } ?> value="1" > <?= lang('Yes'); ?>
                                </div>  
                            <?php } ?>
                                <div class="boxline"> 
                                    <label class="form-label" for="post_content"><?= lang('Translation'); ?>?</label>
                                    <input type="radio" name="translation" <?php if($post['post_translation'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="translation" <?php if($post['post_translation'] == 1) { ?>checked<?php } ?> value="1" > <?= lang('Yes'); ?>
                                </div>    
                            <?php if($uid['trust_level'] > 2) { ?>            
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Raise'); ?>?</label>
                                    <input type="radio" name="top" <?php if($post['post_top'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="top" <?php if($post['post_top']== 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
                                </div>                      
                            <?php } ?>
                            <div class="boxline">  
                                <label class="form-label" for="post_content"><?= lang('Space'); ?></label>
                                <select class="form-input" name="space_id">
                                    <?php foreach ($space as $sp) { ?>
                                        <option <?php if($post['space_id'] == $sp['space_id']) { ?> selected<?php } ?>   value="<?= $sp['space_id']; ?>"> <?= $sp['space_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <br> 
                            </div>
                            
                            <?php if($tags) { ?>
                                <div class="boxline">  
                                    <label class="form-label" for="post_content"><?= lang('Tags'); ?></label>
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
                                    <label class="form-label" for="post_content"><?= lang('Author'); ?></label>
                                    <select name="post_user_new" id='selUser'>
                                        <option value="<?= $user['id']; ?>"><?= $user['login']; ?></option>
                                    </select>
                                </div>  
                            <?php } ?>

                            <?php if($uid['trust_level'] > 0) { ?> 
                                <div class="boxline">  
                                    <label class="form-label" for="post_content"><?= lang('Related'); ?></label>
                                    <select name="post_related[]" multiple="multiple" id='selLinked'>
                                        <?php foreach ($post_related as $related) { ?>
                                            <option selected value="<?= $related['post_id']; ?>"><?= $related['post_title']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script nonce="<?= $_SERVER['nonce']; ?>">
                                        $(document).ready(function(){
                                            $("#selLinked").select2({
                                                width: '70%',
                                                ajax: { 
                                                    url: "/search/posts",
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

                                            // Смена владельца
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
                        </div>
                        <div class="boxline">
                            <br>
                            <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                            <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 