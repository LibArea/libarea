<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <a class="right v-ots" title="<?= lang('Add'); ?>" href="/topic/add"><i class="icon plus"></i></a>
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>
                
                <div class="space">
                    <?php if (!empty($topics)) { ?>
                        <div class="t-table">
                            <div class="t-th">
                                <span class="t-td center">Id</span>
                                <span class="t-td"><?= lang('Logo'); ?></span>
                                <span class="t-td"><?= lang('Info'); ?></span>
                                <span class="t-td center"><?= lang('Action'); ?></span>
                            </div>
                            <?php foreach ($topics as $key => $topic) { ?> 
                                <div class="t-tr">
                                    <span class="t-td width-30 center">
                                        <?= $topic['topic_id']; ?>
                                    </span>  
                                    <span class="t-td width-30 center">
                                        <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'ava-94'); ?>
                                    </span>                            
                                    <span class="t-td">
                                        <a rel="nofollow noreferrer" href="/topic/<?= $topic['topic_slug']; ?>">
                                            <?= $topic['topic_title']; ?>
                                            <i class="icon share-alt"></i>
                                        </a>
                                        | <span class="green">topic/<?= $topic['topic_slug']; ?></span>
                                        | <?= $topic['topic_count']; ?>
                                        <?php if ($topic['topic_is_parent'] == 1) { ?>
                                            |  <span class="red"><?= lang('Root'); ?></span>
                                        <?php } ?>
                                        <?php if ($topic['topic_parent_id'] != 0) { ?>
                                            |  <span class="green"><?= lang('Subtopic'); ?></span>
                                        <?php } ?>
                                        <div class="gray">
                                            <?= $topic['topic_description']; ?>
                                        </div>
                                    </span> 
                                    <span class="t-td width-30 center">
                                        <a title="<?= lang('Edit'); ?>" href="/topic/edit/<?= $topic['topic_id']; ?>">
                                           <i class="icon pencil"></i>
                                        </a>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                        
                    <?php } else { ?>
                        <div class="no-content"><?= lang('No'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/topics'); ?>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>