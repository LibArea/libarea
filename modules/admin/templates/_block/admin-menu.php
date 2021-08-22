<aside class="left p0 bg-gray-800 fixed">
    <div class="mt15 mr15 mb5 ml15 gray size-21">
        <a title="<?= lang('Admin'); ?>" class="light-gray" href="/admin"><?= lang('Admin'); ?></a>
    </div>
    <div class="ml15 gray size-13">--------------------------------------------</div>
    <div class="logo size-15 flex">
        <a title="<?= lang('in-the-profile'); ?>" class="light-gray" href="/u/<?= $uid['user_login']; ?>">
            <?= user_avatar_img($uid['user_avatar'], 'max', $uid['user_id'], 'ava-24 mr5 ml15'); ?>
            <?= $uid['user_login']; ?>
        </a>
    </div>
    <div class="ml15 gray size-13">--------------------------------------------</div>
    <ul class="p0 mt5">
        <li class="nav<?php if ($data['sheet'] == 'admin') { ?> active<?php } ?>">
            <a title="<?= lang('Aadmin'); ?>" href="/admin">
                <i class="icon-tools middle mr10"></i>
                <span class="size-15 middle"><?= lang('Admin'); ?></span>
            </a>
        <li>
        <li class="nav<?php if ($data['sheet'] == 'users' || $data['sheet'] == 'users-ban') { ?> active<?php } ?>">
            <a title="<?= lang('Users'); ?>" href="/admin/users">
                <i class="icon-user-o middle mr10"></i>
                <span class="size-15 middle"><?= lang('Users'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'reports') { ?> active<?php } ?>">
            <a title="<?= lang('Reports'); ?>" href="/admin/reports">
                <i class="icon-warning-empty middle mr10"></i>
                <span class="size-15 middle"><?= lang('Reports'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'audits' || $data['sheet'] == 'audits') { ?> active<?php } ?>">
            <a title="<?= lang('Audit'); ?>" href="/admin/audits">
                <i class="icon-lightbulb middle mr10"></i>
                <span class="size-15 middle"><?= lang('Audit'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'spaces' || $data['sheet'] == 'spaces-ban') { ?> active<?php } ?>">
            <a title="<?= lang('Spaces'); ?>" href="/admin/spaces">
                <i class="icon-infinity middle mr10"></i>
                <span class="size-15 middle"><?= lang('Spaces'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'topics' || $data['sheet'] == 'topics-add') { ?> active<?php } ?>">
            <a title="<?= lang('Topics'); ?>" href="/admin/topics">
                <i class="icon-clone middle mr10"></i>
                <span class="size-15 middle"><?= lang('Topics'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'invitations') { ?> active<?php } ?>">
            <a title="<?= lang('Invites'); ?>" href="/admin/invitations">
                <i class="icon-user-add-outline middle mr10"></i>
                <span class="size-15 middle"><?= lang('Invites'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'posts' || $data['sheet'] == 'posts-ban') { ?> active<?php } ?>">
            <a title="<?= lang('Invites'); ?>" href="/admin/posts">
                <i class="icon-book-open middle mr10"></i>
                <span class="size-15 middle"><?= lang('Posts'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'comments' || $data['sheet'] == 'comments-ban') { ?> active<?php } ?>">
            <a title="<?= lang('Comments-n'); ?>" href="/admin/comments">
                <i class="icon-commenting-o middle mr10"></i>
                <span class="size-15 middle"><?= lang('Comments-n'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'answers' || $data['sheet'] == 'answers-ban') { ?> active<?php } ?>">
            <a title="<?= lang('Answers-n'); ?>" href="/admin/answers">
                <i class="icon-comment-empty middle mr10"></i>
                <span class="size-15 middle"><?= lang('Answers-n'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'badges') { ?> active<?php } ?>">
            <a title="<?= lang('Badges'); ?>" href="/admin/badges">
                <i class="icon-award middle mr10"></i>
                <span class="size-15 middle"><?= lang('Badges'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'domains' || $data['sheet'] == 'domains-add') { ?> active<?php } ?>">
            <a title="<?= lang('Domains'); ?>" href="/admin/webs">
                <i class="icon-link middle mr10"></i>
                <span class="size-15 middle"><?= lang('Domains'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'words') { ?> active<?php } ?>">
            <a title="<?= lang('Stop words'); ?>" href="/admin/words">
                <i class="icon-info middle mr10"></i>
                <span class="size-15 middle"><?= lang('Stop words'); ?></span>
            </a>
        </li>
    </ul>

    <div class="center gray size-13">
        ------------------------
        <br>
        Loriup &copy; <?= date('Y'); ?>
    </div>
</aside>