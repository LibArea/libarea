<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/topics', lang('Topics'), null, null, $data['meta_title']); ?>
                
                <div class="telo space">
                    <div class="box create">
                        <form action="/topic/create" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="boxline">
                                <label class="form-label" for="post_content">
                                    <?= lang('Title'); ?><sup class="red">*</sup>
                                </label>
                                <input class="form-input" minlength="3" type="text" name="topic_title" value="">
                                <div class="box_h">3 - 64 <?= lang('characters'); ?></div>
                            </div>
                            <div class="boxline">
                                <label class="form-label" for="post_content">
                                    <?= lang('Title'); ?> (SEO)<sup class="red">*</sup>
                                </label>
                                <input class="form-input" minlength="4" type="text" name="topic_seo_title" value="">
                                <div class="box_h">4 - 225 <?= lang('characters'); ?></div>
                            </div>
                             <div class="boxline">
                                <label class="form-label" for="post_content">
                                    <?= lang('Slug'); ?><sup class="red">*</sup>
                                </label>
                                <input class="form-input" minlength="3" type="text" name="topic_slug" value="">
                                <div class="box_h">3 - 32 <?= lang('characters'); ?></div>
                            </div>
                            <div class="boxline">
                                <label for="post_content">
                                    <?= lang('Meta Description'); ?><sup class="red">*</sup>
                                </label>
                                <textarea rows="6" class="add" name="topic_description"></textarea>
                                <div class="box_h">> 44 <?= lang('characters'); ?></div>
                            </div>
                                <input type="submit" name="submit"  class="button" value="<?= lang('Add'); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
     <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('info_space_edit'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 