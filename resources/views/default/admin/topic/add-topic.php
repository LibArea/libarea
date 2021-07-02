<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / 
                    <a href="/admin/topics"><?= lang('Topics'); ?></a> / 
                    <span class="red"><?= $data['meta_title']; ?></span>
                </h1>
                
                <div class="telo space">
                    <div class="box create">
                        <form action="/admin/addtopicadmin" method="post" enctype="multipart/form-data">
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
                                <input class="form-input" minlength="14" type="text" name="topic_seo_title" value="">
                                <div class="box_h">14 - 225 <?= lang('characters'); ?></div>
                            </div>
                             <div class="boxline">
                                <label class="form-label" for="post_content">
                                    <?= lang('Slug'); ?><sup class="red">*</sup>
                                </label>
                                <input class="form-input" minlength="3" type="text" name="topic_slug" value="">
                                <div class="box_h">3 - 32 <?= lang('characters'); ?></div>
                            </div>
                            <div class="boxline">
                                <label for="post_content"><?= lang('Description'); ?><sup class="red">*</sup></label>
                                <textarea class="add" name="topic_description"></textarea>
                            </div>
                                <input type="submit" name="submit"  class="button" value="<?= lang('Add'); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 