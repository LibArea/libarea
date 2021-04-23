<aside id="sidebar"> 
    <div class="menu-info">
        <a href="/u/<?= Request::get('login'); ?>">
            <svg class="md-icon">
                <use xlink:href="/assets/svg/icons.svg#user"></use>
            </svg>
            <?= lang('Profile'); ?>
        </a>
        <?php if($uid['id'] > 0) { ?>

            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/setting/avatar' || $uid['uri'] == '/u/'.$uid['login'].'/setting' || $uid['uri'] == '/u/'.$uid['login'].'/setting/security') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/setting">
                <svg class="md-icon">
                    <use xlink:href="/assets/svg/icons.svg#settings"></use>
                </svg>
                <?= lang('Settings'); ?>
            </a> 
        
            <div class="v-ots"></div>
            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/notifications') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/notifications">
                <svg class="md-icon">
                    <use xlink:href="/assets/svg/icons.svg#bulb"></use>
                </svg>
                <?= lang('Notifications'); ?>
            </a> 

            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/messages') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/messages">
                <svg class="md-icon">
                    <use xlink:href="/assets/svg/icons.svg#mail"></use>
                </svg>
                <?= lang('Messages'); ?>
            </a> 

            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/favorite') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/favorite">
                <svg class="md-icon">
                    <use xlink:href="/assets/svg/icons.svg#bookmark"></use>
                </svg>
                <?= lang('Favorites'); ?>
            </a>  
        
            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/invitation') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/invitation">
                <svg class="md-icon">
                    <use xlink:href="/assets/svg/icons.svg#link"></use>
                </svg>
                <?= lang('Invites'); ?>
            </a>  
        <?php } ?>
    </div>
</aside>