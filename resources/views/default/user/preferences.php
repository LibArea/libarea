<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['login'], lang('Profile'), $data['h1']); ?>
                
                <ul class="nav-tabs">
                  <?php if ($data['sheet'] == 'favorite') { ?>
                    <li class="active">
                      <span><?= lang('Favorites'); ?></span>
                    </li>
                    <li>
                      <a href="/u/<?= $uid['login']; ?>/preferences">
                        <span><?= lang('Preferences'); ?></span>
                      </a>
                    </li>
                  <?php } elseif ($data['sheet'] == 'preferences') { ?>
                    <li>
                      <a href="/u/<?= $uid['login']; ?>/favorite">
                        <span><?= lang('Favorites'); ?></span>
                      </a>
                    </li>
                    <li class="active">
                      <span><?= lang('Preferences'); ?></span>
                    </li>
                  <?php } ?>
                </ul>
            </div>
        </div>

        <?php include TEMPLATE_DIR . '/_block/post.php'; ?>

    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('info_preferences'); ?>...
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>