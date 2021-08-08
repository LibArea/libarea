<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1><?= $data['h1']; ?></h1>

                <?php if (!empty($result)) { ?>
                    <div>Вы искали: <b><?= $query; ?></b></div>
                <?php } ?>

                <br>

                <?php if (!empty($result)) { ?>

                    <?php if (Lori\Config::get(Lori\Config::PARAM_SEARCH) == 0) { ?>
                        <?php foreach ($result as  $post) { ?>
                            <div class="search max-width mb20">
                                <a class="search-title size-21" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug'] ?>">
                                    <?= $post['post_title']; ?>
                                </a>
                                <span class="no-md"><?= $post['post_content']; ?>...</span>
                            </div>
                            <div class="mt15 mb15"></div>
                        <?php } ?>
                    <?php } else { ?>
                        <?php foreach ($result as  $post) { ?>
                            <div class="search max-width mb20">
                                <div class="search-info gray size-13 lowercase">
                                    <?= spase_logo_img($post['space_img'], 'small', $post['space_name'], 'ava mr5'); ?>
                                    <a class="search-info gray lowercase" href="/s/<?= $post['space_slug']; ?>"><?= $post['space_name']; ?></a>
                                    — <?= lang('Like'); ?> <?= $post['post_votes']; ?>
                                </div>
                                <a class="search-title" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug'] ?>"><?= $post['_title']; ?></a><br>
                                <span class="no-md"><?= $post['_content']; ?></span>
                            </div>
                            <div class="mt15 mb15"></div>
                        <?php } ?>
                    <?php } ?>

                <?php } else { ?>
                    <p>Поиск не дал результатов...
                    <p>
                    <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('info_search'); ?>

                <i><?= lang('Under development'); ?></i>

            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>