<ul class="nav-tabs mt0 mb15">
    <?php if ($data['sheet'] == 'setting') { ?>
        <li class="active">
            <span><?= lang('Setting profile'); ?></span>
        </li>
    <?php } else { ?>
        <li>
            <a href="/u/<?= $uid['user_login']; ?>/setting">
                <span><?= lang('Setting profile'); ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if ($data['sheet'] == 'avatar') { ?>
        <li class="active">
            <span><?= lang('Avatar'); ?></span>
        </li>
    <?php } else { ?>
        <li>
            <a href="/u/<?= $uid['user_login']; ?>/setting/avatar">
                <span><?= lang('Avatar'); ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if ($data['sheet'] == 'security') { ?>
        <li class="active">
            <span><?= lang('Password'); ?></span>
        </li>
    <?php } else { ?>
        <li>
            <a href="/u/<?= $uid['user_login']; ?>/setting/security">
                <span><?= lang('Password'); ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if ($data['sheet'] == 'notifications') { ?>
        <li class="active">
            <span><?= lang('Notifications'); ?></span>
        </li>
    <?php } else { ?>
        <li>
            <a href="/u/<?= $uid['user_login']; ?>/setting/notifications">
                <span><?= lang('Notifications'); ?></span>
            </a>
        </li>
    <?php } ?>
</ul>