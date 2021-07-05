<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                    <a class="right" href="/admin/topic/add"><?= lang('Add'); ?></a>
                </h1>

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
                                    <span class="t-td w-30 center">
                                        <?= $topic['topic_id']; ?>
                                    </span>  
                                    <span class="t-td w-30 center">
                                        <img class="space-logo" src="<?= topic_url($topic['topic_img'], 'max'); ?>">
                                    </span>                            
                                    <span class="t-td">
                                        <a rel="nofollow noreferrer" href="/topic/<?= $topic['topic_slug']; ?>">
                                            <?= $topic['topic_title']; ?>
                                        </a>
                                        | <span class="green">topic/<?= $topic['topic_slug']; ?></span>
                                        | <?= $topic['topic_count']; ?>
                                        <?php if ($topic['topic_is_parent'] == 1) { ?>
                                            |  <span class="red"><?= lang('Root'); ?></span>
                                        <?php } ?>
                                        <?php if ($topic['topic_parent_id'] != 0) { ?>
                                            |  <span class="green"><?= lang('Subtopic'); ?></span>
                                        <?php } ?>
                                        <a class="right" target="_blank" rel="noopener noreferrer" href="/topic/<?= $topic['topic_slug']; ?>"><i class="icon share-alt"></i></a>
                                        <br> 
                                        <?= $topic['topic_description']; ?>
                                    </span> 
                                    <span class="t-td w-30 center">
                                         <a title="<?= lang('Edit'); ?>" href="/admin/topic/<?= $topic['topic_id']; ?>/edit/">
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
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>