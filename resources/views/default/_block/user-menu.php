<div class="white-box sticky">
    <div class="inner-padding big">
        <div class="menu-info">
            <a title="<?= lang('Profile'); ?>" href="/u/<?= Request::get('login'); ?>">
                <i class="icon user"></i>
                <?= lang('Profile'); ?>  
            </a>
            <a title="<?= lang('Posts'); ?>" <?php if($uid['uri'] == '/u/'.Request::get('login').'/posts') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/posts">
                <i class="icon doc"></i>
                <?= lang('Posts'); ?>
            </a>
            <a title="<?= lang('Answers-n'); ?>" <?php if($uid['uri'] == '/u/'.Request::get('login').'/answers') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/answers">
                <i class="icon action-undo"></i>
                <?= lang('Answers-n'); ?>
            </a>
            <a title="<?= lang('Comments-n'); ?>" <?php if($uid['uri'] == '/u/'.Request::get('login').'/comments') { ?>class="active"<?php } ?> href="/u/<?= Request::get('login'); ?>/comments">
                <i class="icon bubbles"></i>
                <?= lang('Comments-n'); ?>
            </a>
        </div>
    </div>
</div>
