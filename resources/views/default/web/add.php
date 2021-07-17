<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / 
                    <a href="/admin/domains"><?= lang('Domains'); ?></a> /
                    <span class="red"><?= $data['meta_title']; ?></span>
                </h1>
                <div class="telo space">
                    <div class="box create">
                        <form action="/web/create" method="post">
                            <?= csrf_field() ?>
                            <div class="boxline max-width">
                                <label class="form-label" for="post_title">URL</label>
                                <input class="form-input" type="text" name="link_url" value="">
                            </div>
                            <div class="boxline max-width">
                                <label class="form-label" for="post_title"><?= lang('Title'); ?></label>
                                <input class="form-input" type="text" name="link_title" value="" required>
                                <div class="box_h">24 - 250 <?= lang('characters'); ?> («Газета.Ru» — интернет-газета)</div>
                            </div>
                            <div class="boxline max-width">
                                <label class="form-label"  for="post_title"><?= lang('Description'); ?></label>            
                                <textarea name="link_content" required></textarea>
                                <div class="box_h">24 - 1500 <?= lang('characters'); ?></div>
                            </div>
                            <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('info-url-edit'); ?>
            </div>
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>