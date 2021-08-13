<div class="white-box sticky">
    <div class="p15">
        <div class="menu-info">
            <a title="<?= lang('Profile'); ?>" class="mb5 size-15 block gray" href="/u/<?= Request::get('login'); ?>">
                <i class="icon-user-o middle"></i>
                <span class="middle"><?= lang('Profile'); ?></span>
            </a>
            <a title="<?= lang('Posts'); ?>" class="mb5 size-15 block gray<?php if ($uid['uri'] == '/u/' . Request::get('login') . '/posts') { ?> red<?php } ?>" href="/u/<?= Request::get('login'); ?>/posts">
                <i class="icon-book-open middle"></i>
                <span class="middle"><?= lang('Posts'); ?></span>
            </a>
            <a title="<?= lang('Answers-n'); ?>" class="mb5 size-15 block gray<?php if ($uid['uri'] == '/u/' . Request::get('login') . '/answers') { ?> red<?php } ?>" href="/u/<?= Request::get('login'); ?>/answers">
                <i class="icon-comment-empty middle"></i>
                <span class="middle"><?= lang('Answers-n'); ?></span>
            </a>
            <a title="<?= lang('Comments-n'); ?>" class="mb5 size-15 block gray<?php if ($uid['uri'] == '/u/' . Request::get('login') . '/comments') { ?> red<?php } ?>" href="/u/<?= Request::get('login'); ?>/comments">
                <i class="icon-commenting-o middle"></i>
                <span class="middle"><?= lang('Comments-n'); ?></span>
            </a>
        </div>
    </div>
</div>