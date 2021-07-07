<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <a class="right" target="_blank" rel="noopener noreferrer" href="/topic/<?= $topic['topic_slug']; ?>">
                    <i class="icon share-alt"></i>
                </a>
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / 
                    <a href="/admin/topics"><?= lang('Topics'); ?></a> / 
                    <span class="red"><?= $data['meta_title']; ?></span>
                </h1>
                <div class="telo topic">
                    <div class="box create ">
                    
                        <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'img-topic-edit'); ?>
                        <form action="/admin/topic/edit/<?= $topic['topic_id']; ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                             
                            <div class="box-form-img"> 
                                <div class="boxline">
                                    <div class="input-images"></div>
                                </div>
                            </div> 
                            <div class="clear">
                                <p><?= lang('select-file-up'); ?>: 240x240px (jpg, jpeg, png)</p>
                                <p><input type="submit" class="button" value="<?= lang('Download'); ?>"/></p>
                            </div>
                            <div class="boxline">
                                <label class="form-label" for="topic_content">
                                    <?= lang('Title'); ?><sup class="red">*</sup>
                                </label>
                                <input class="form-input" minlength="3" type="text" name="topic_title" value="<?= $topic['topic_title']; ?>">
                                <div class="box_h">3 - 64 <?= lang('characters'); ?></div>
                            </div>
                            <div class="boxline">
                                <label class="form-label" for="topic_content">
                                    <?= lang('Title'); ?> (SEO)<sup class="red">*</sup>
                                </label>
                                <input class="form-input" minlength="4" name="topic_seo_title" value="<?= $topic['topic_seo_title']; ?>">
                                <div class="box_h">4 - 225 <?= lang('characters'); ?></div>
                            </div>
                             <div class="boxline">
                                <label class="form-label" for="topic_content">
                                    <?= lang('Slug'); ?><sup class="red">*</sup>
                                </label>
                                <input class="form-input" minlength="3" type="text" name="topic_slug" value="<?= $topic['topic_slug']; ?>">
                                <div class="box_h">3 - 32 <?= lang('characters'); ?></div>
                            </div>
                             <div class="boxline">
                                <label class="form-label" for="topic_content">
                                    <?= lang('topic-count'); ?><sup class="red">*</sup>
                                </label>
                                <input class="form-input"  type="text" name="topic_count" value="<?= $topic['topic_count']; ?>">
                            </div>
                            <?php if($topic['topic_parent_id'] > 0) { ?>
                                <div class="boxline">
                                    <label for="topic_content"><?= lang('Корневая'); ?>?</label>
                                    ----
                                </div>    
                            <?php } else { ?>
                                <div class="boxline"> 
                                    <label for="topic_content"><?= lang('Корневая'); ?>?</label>
                                    <input type="radio" name="topic_is_parent" <?php if($topic['topic_is_parent'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="topic_is_parent" <?php if($topic['topic_is_parent'] == 1) { ?>checked<?php } ?> value="1" >  <?= lang('Yes'); ?>
                                    <div class="box_h"><?= lang('root-help'); ?></div>
                                </div> 
                            <?php } ?>
                            <div class="boxline">
                                <label for="topic_content">
                                    <?= lang('Meta Description'); ?><sup class="red">*</sup>
                                </label>
                                <textarea class="add" name="topic_description"><?= $topic['topic_description']; ?></textarea>
                                <div class="box_h">> 44 <?= lang('characters'); ?></div>
                            </div>
                            <div class="boxline">
                                <label for="topic_content"><?= lang('Info'); ?><sup class="red">*</sup></label>
                                <textarea class="add" name="topic_info"><?= $topic['topic_info']; ?></textarea>
                                <div class="box_h">Markdown, > 14 <?= lang('characters'); ?></div>
                            </div>
                            <?php if($topic['topic_is_parent'] != 1) { ?>
                                <div class="boxline">
                                    <label class="form-label" for="topic_content"><?= lang('Root'); ?></label>
                                    <select name="topic_parent_id[]" multiple="multiple" id='selMainLinked'>
                                        <?php foreach ($topic_parent_id as $parent) { ?>
                                            <option selected value="<?= $parent['topic_id']; ?>"><?= $parent['topic_title']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                            
                            <div class="boxline">  
                                <label class="form-label" for="post_content">
                                    <?= lang('Related'); ?> post
                                </label>
                                <select name="post_related[]" multiple="multiple" id='postRelated'>
                                    <?php foreach ($post_related as $related) { ?>
                                        <option selected value="<?= $related['post_id']; ?>"><?= $related['post_title']; ?></option>
                                    <?php } ?>
                                </select>
                            <div class="boxline">  
                                    <label class="form-label" for="topic_content">
                                        <?= lang('Related'); ?> topic
                                    </label>
                                    <select name="topic_related[]" multiple="multiple" id='topicRelated'>
                                        <?php foreach ($topic_related as $related) { ?>
                                            <option selected value="<?= $related['topic_id']; ?>"><?= $related['topic_title']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <script nonce="<?= $_SERVER['nonce']; ?>">
                                        $(document).ready(function(){
                                            $("#topicRelated").select2({
                                                width: '70%',
                                                ajax: { 
                                                    url: "/search/topics",
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
                                            $("#selMainLinked").select2({
                                                width: '70%',
                                                maximumSelectionLength: 1,
                                                ajax: { 
                                                    url: "/search/topics/main",
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
                                            $("#postRelated").select2({
                                                width: '70%',
                                                maximumSelectionLength: 5,
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
                                        });
                                    </script>
                                </div>  
                            <div class="boxline">
                                <input type="hidden" name="topic_id" value="<?= $topic['topic_id']; ?>">
                                <input type="submit" name="submit"  class="button" value="<?= lang('Add'); ?>" />
                            </div>    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 