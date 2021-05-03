<aside class="sidebar"> 
    <div class="menu-info">
        <a href="/u/<?= Request::get('login'); ?>">
            <icon name="user"></icon>
            <?= lang('Profile'); ?>  
        </a>
        <a <?php if($uid['uri'] == '/u/'.Request::get('login').'/posts') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/posts">
            <icon name="doc"></icon>
            <?= lang('Posts'); ?>
        </a>
        <a <?php if($uid['uri'] == '/u/'.Request::get('login').'/answers') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/answers">
            <icon name="action-undo"></icon>
            <?= lang('Answers'); ?>
        </a>
        <a <?php if($uid['uri'] == '/u/'.Request::get('login').'/comments') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/comments">
            <icon name="bubbles"></icon>
            <?= lang('Comments'); ?>
        </a>
  
    </div>
</aside>