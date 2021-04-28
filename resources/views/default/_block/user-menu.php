<aside id="sidebar"> 
    <div class="menu-info">
        <a href="/u/<?= Request::get('login'); ?>">
            <icon name="user"></icon>
            <?= lang('Profile'); ?>
        </a>
        <?php if($uid['id'] > 0) { ?>
            <div class="v-ots"></div>
            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/setting/avatar' || $uid['uri'] == '/u/'.$uid['login'].'/setting' || $uid['uri'] == '/u/'.$uid['login'].'/setting/security') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/setting">
                <icon name="settings"></icon>
                <?= lang('Settings'); ?>
            </a> 
        
            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/notifications') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/notifications">
                <icon name="bell"></icon>
                <?= lang('Notifications'); ?>
            </a> 

            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/messages') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/messages">
                <icon name="envelope"></icon>
                <?= lang('Messages'); ?>
            </a> 

            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/favorite') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/favorite">
                <icon name="star"></icon>
                <?= lang('Favorites'); ?>
            </a>  
        
            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/invitation') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/invitation">
                <icon name="link"></icon>
                <?= lang('Invites'); ?>
            </a>  
        <?php } ?>
    </div>
</aside>