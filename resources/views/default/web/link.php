<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15 space-tags">
                <?php if ($link['link_title']) { ?>
                    <div class="right heart-link">
                        <?= votes($uid['user_id'], $link, 'link'); ?>
                    </div>
                    <h1><?= $link['link_title']; ?>
                        <?php if ($uid['user_trust_level'] > 4) { ?>
                            <span class="mr5 ml5"></span>
                            <a class="size-13" title="<?= lang('Edit'); ?>" href="/admin/webs/<?= $link['link_id']; ?>/edit">
                                <i class="icon-pencil size-15"></i>
                            </a>
                        <?php } ?>
                    </h1>
                    <div class="gray">
                        <?= $link['link_content']; ?>
                    </div>
                    <div class="domain-footer-small">
                        <a class="green" rel="nofollow noreferrer ugc" href="<?= $link['link_url']; ?>">
                            <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
                            <?= $link['link_url']; ?>
                        </a>

                        <span class="right"><?= $link['link_count']; ?></span>
                    </div>
                <?php } else { ?>
                    <h1><?= $data['h1']; ?></h1>
                <?php } ?>
            </div>
        </div>

        <?php include TEMPLATE_DIR . '/_block/post.php'; ?>

        <?= pagination($data['pNum'], $data['pagesCount'], null, '/domain/' . $link['link_url_domain']); ?>

    </main>
    <aside>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15 space-tags">
                <?php if (!empty($domains)) { ?>
                    <div class="uppercase mb5 mt5 size-13"><?= lang('Domains'); ?></div>
                    <?php foreach ($domains as  $domain) { ?>
                        <a class="size-13 gray" href="/domain/<?= $domain['link_url_domain']; ?>">
                            <i class="icon-link middle"></i> <?= $domain['link_url_domain']; ?>
                            <sup class="size-13"><?= $domain['link_count']; ?></sup>
                        </a><br>
                    <?php } ?>
                <?php } else { ?>
                    <p><?= lang('There are no domains'); ?>...</p>
                <?php } ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>