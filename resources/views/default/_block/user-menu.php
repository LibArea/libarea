<aside class="sidebar"> 
    <div class="menu-info">
        <a href="/u/<?= Request::get('login'); ?>">
            <i class="icon user"></i>
            <?= lang('Profile'); ?>  
        </a>
        <a <?php if($uid['uri'] == '/u/'.Request::get('login').'/posts') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/posts">
            <i class="icon doc"></i>
            <?= lang('Posts'); ?>
        </a>
        <a <?php if($uid['uri'] == '/u/'.Request::get('login').'/answers') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/answers">
            <i class="icon action-undo"></i>
            <?= lang('Answers'); ?>
        </a>
        <a <?php if($uid['uri'] == '/u/'.Request::get('login').'/comments') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/comments">
            <i class="icon bubbles"></i>
            <?= lang('Comments'); ?>
        </a>
  
    </div>
</aside>