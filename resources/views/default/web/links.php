<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15 space-tags">
                <?php if ($uid['trust_level'] == 5) { ?>
                    <a title="<?= lang('Add'); ?>" class="right mb5" href="/web/add">
                        <i class="light-icon-plus middle"></i>
                    </a>
                <?php } ?>
                <h1><?= $data['h1']; ?></h1>
            </div>
        </div>

        <?php if (!empty($links)) { ?>
            <?php foreach ($links as $key => $link) { ?>
                <div class="white-box">
                    <a href="/domain/<?= $link['link_url_domain']; ?>">
                        <h2 class="title size-21 pt15 ml15 mb0">
                            <?php if ($link['link_title']) { ?>
                                <?= $link['link_title']; ?>
                            <?php } else { ?>
                                Add title...
                            <?php } ?>
                        </h2>
                    </a>
                    <?php if ($uid['trust_level'] == 5) { ?>
                        <a class="size-13 right" title="<?= lang('Edit'); ?>" href="/admin/webs/<?= $link['link_id']; ?>/edit">
                            <i class="light-icon-edit"></i>
                        </a>
                    <?php } ?>
                    <span class="green ml15">
                        <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
                        <?= $link['link_url_domain']; ?>
                    </span>
                    <div class="gray ml15">
                        <?php if ($link['link_content']) { ?>
                            <?= $link['link_content']; ?>
                        <?php } else { ?>
                            Add content...
                        <?php } ?>
                    </div>
                    <div class="pt5 pr15 pb5 pl15 hidden lowercase">
                        <?= votes($uid['id'], $link, 'link'); ?>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="no-content"><?= lang('No'); ?>...</div>
        <?php } ?>

        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/domains'); ?>
    </main>
    <aside>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15 space-tags">
                <?= lang('domains-desc'); ?>.
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>