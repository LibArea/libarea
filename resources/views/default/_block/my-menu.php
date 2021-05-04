<aside class="sidebar"> 
    <div class="menu-info">
        <a href="/u/<?= Request::get('login'); ?>">
            <i class="icon user"></i>
            <?= lang('Profile'); ?>
        </a>
        <?php if($uid['id'] > 0) { ?>
            <div class="v-ots"></div>
            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/setting/avatar' || $uid['uri'] == '/u/'.$uid['login'].'/setting' || $uid['uri'] == '/u/'.$uid['login'].'/setting/security') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/setting">
                <i class="icon settings"></i>
                <?= lang('Settings'); ?>
            </a> 
        
            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/notifications') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/notifications">
                <i class="icon bell"></i>
                <?= lang('Notifications'); ?>
            </a> 

            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/messages') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/messages">
                <i class="icon envelope"></i>
                <?= lang('Messages'); ?>
            </a> 

            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/favorite') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/favorite">
                <i class="icon star"></i>
                <?= lang('Favorites'); ?>
            </a>  
        
            <a <?php if($uid['uri'] == '/u/'.$uid['login'].'/invitation') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/invitation">
                <i class="icon link"></i>
                <?= lang('Invites'); ?>
            </a>  
        <?php } ?>
    </div>
</aside>