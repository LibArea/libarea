<?php include TEMPLATE_DIR . '/header.php'; ?>

<?php if ($space['space_is_delete'] == 0) { ?>

    <?php include TEMPLATE_DIR . '/space/banner_top.php'; ?>
    <div class="wrap">
        <main>
            <ul class="nav-tabs mt0">
                <?php if ($data['sheet'] == 'feed') { ?>
                    <li class="active">
                        <span><?= lang('Feed'); ?></span>
                    </li>
                    <li>
                        <a href="/s/<?= $space['space_slug']; ?>/top">
                            <span>Top</span>
                        </a>
                    </li>
                    <li>
                        <a href="/s/<?= $space['space_slug']; ?>/writers">
                            <span>Писатели</span>
                        </a>
                    </li>
                <?php } elseif ($data['sheet'] == 'top') { ?>
                    <li>
                        <a href="/s/<?= $space['space_slug']; ?>">
                            <span><?= lang('Feed'); ?></span>
                        </a>
                    </li>
                    <li class="active">
                        <span>Top</span>
                    </li>
                    <li>
                        <a href="/s/<?= $space['space_slug']; ?>/writers">
                            <span>Писатели</span>
                        </a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="/s/<?= $space['space_slug']; ?>">
                            <span><?= lang('Feed'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="/s/<?= $space['space_slug']; ?>/top">
                            <span>Top</span>
                        </a>
                    </li>
                    <li class="active">
                        <span>Писатели</span>
                    </li>
                <?php } ?>
                <?php if ($uid['user_trust_level'] == 5 || $space['space_user_id'] == $uid['user_id']) { ?>
                    <li class="right">
                        <a class="edit-space" href="/space/edit/<?= $space['space_id']; ?>">
                            <span><?= lang('Edit'); ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <?php if ($data['sheet'] == 'writers') { ?>
                <?php include TEMPLATE_DIR . '/space/writers.php'; ?>
            <?php } else { ?>
                <?php include TEMPLATE_DIR . '/_block/post.php'; ?>
                 <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/s/' . $space['space_slug']); ?>
            <?php } ?>
        </main>
        
        <?php include TEMPLATE_DIR . '/space/sidebar.php'; ?>

    </div>
<?php } else { ?>
    <main class="w-100">
        <?= no_content('ban-space'); ?>
    </main>
<?php } ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>