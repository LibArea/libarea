<ul class="nav-tabs mt0 mb15">
    <?php if ($data['sheet'] == 'favorites') { ?>
        <li class="active">
            <span><?= lang('Favorites'); ?></span>
        </li>
    <?php } else { ?>
        <li>
            <a href="/u/<?= $uid['user_login']; ?>/favorite">
                <span><?= lang('Favorites'); ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if ($data['sheet'] == 'subscribed') { ?>
        <li class="active">
            <span><?= lang('Subscribed'); ?></span>
        </li>
    <?php } else { ?>
         <li>
            <a href="/u/<?= $uid['user_login']; ?>/subscribed">
                <span><?= lang('Subscribed'); ?></span>
            </a>
        </li>
    <?php } ?> 
</ul>