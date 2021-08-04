<div class="white-box sticky">
    <div class="p15">
        <div class="menu-info">
            <a title="<?= lang('Profile'); ?>" class="gray" href="/u/<?= Request::get('login'); ?>">
                <i class="light-icon-user middle"></i>
                <span class="middle"><?= lang('Profile'); ?></span>
            </a>
            <a title="<?= lang('Posts'); ?>" class="gray<?php if ($uid['uri'] == '/u/' . Request::get('login') . '/posts') { ?> active<?php } ?>" href="/u/<?= Request::get('login'); ?>/posts">
                <i class="light-icon-book middle"></i>
                <span class="middle"><?= lang('Posts'); ?></span>
            </a>
            <a title="<?= lang('Answers-n'); ?>" class="gray<?php if ($uid['uri'] == '/u/' . Request::get('login') . '/answers') { ?> active<?php } ?>" href="/u/<?= Request::get('login'); ?>/answers">
                <i class="light-icon-message middle"></i>
                <span class="middle"><?= lang('Answers-n'); ?></span>
            </a>
            <a title="<?= lang('Comments-n'); ?>" class="gray<?php if ($uid['uri'] == '/u/' . Request::get('login') . '/comments') { ?> active<?php } ?>" href="/u/<?= Request::get('login'); ?>/comments">
                <i class="light-icon-messages middle"></i>
                <span class="middle"><?= lang('Comments-n'); ?></span>
            </a>
        </div>
    </div>
</div>