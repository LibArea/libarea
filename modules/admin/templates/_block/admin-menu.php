<aside>
    <div class="mt15 mr15 mb5 ml15 gray size-21">
        <a title="<?= lang('Admin'); ?>" class="light-gray" href="/admin"><?= lang('Admin'); ?></a>
    </div>
    <div class="ml15 gray size-13">--------------------------------------------</div>
    <div class="logo size-15 flex">
        <a title="<?= lang('in-the-profile'); ?>" class="light-gray" href="/u/<?= $uid['login']; ?>">
            <?= user_avatar_img($uid['avatar'], 'max', $uid['id'], 'ava-24 mr5 ml15'); ?>
            <?= $uid['login']; ?>
        </a>
    </div>
    <div class="ml15 gray size-13">--------------------------------------------</div>
    <ul class="p0 mt5">
        <li class="nav<?php if ($data['sheet'] == 'admin') { ?> active<?php } ?>">
            <a title="<?= lang('Aadmin'); ?>" href="/admin">
                <i class="icon-tools middle"></i>
                <span class="size-15 middle"><?= lang('Admin'); ?></span>
            </a>
        <li>
        <li class="nav<?php if ($data['sheet'] == 'userall' || $data['sheet'] == 'banuser') { ?> active<?php } ?>">
            <a title="<?= lang('Users'); ?>" href="/admin/users">
                <i class="icon-user-o middle"></i>
                <span class="size-15 middle"><?= lang('Users'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'all' || $data['sheet'] == 'ban') { ?> active<?php } ?>">
            <a title="<?= lang('Audit'); ?>" href="/admin/audits">
                <i class="icon-lightbulb middle"></i>
                <span class="size-15 middle"><?= lang('Audit'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'allspaces' || $data['sheet'] == 'banspaces') { ?> active<?php } ?>">
            <a title="<?= lang('Spaces'); ?>" href="/admin/spaces">
                <i class="icon-infinity middle"></i>
                <span class="size-15 middle"><?= lang('Spaces'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'topics') { ?> active<?php } ?>">
            <a title="<?= lang('Topics'); ?>" href="/admin/topics">
                <i class="icon-clone middle"></i>
                <span class="size-15 middle"><?= lang('Topics'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'invitations') { ?> active<?php } ?>">
            <a title="<?= lang('Invites'); ?>" href="/admin/invitations">
                <i class="icon-user-add-outline middle"></i>
                <span class="size-15 middle"><?= lang('Invites'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'postall' || $data['sheet'] == 'postban') { ?> active<?php } ?>">
            <a title="<?= lang('Invites'); ?>" href="/admin/posts">
                <i class="icon-book-open middle"></i>
                <span class="size-15 middle"><?= lang('Posts'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'commentall' || $data['sheet'] == 'commentban') { ?> active<?php } ?>">
            <a title="<?= lang('Comments-n'); ?>" href="/admin/comments">
                <i class="icon-commenting-o middle"></i>
                <span class="size-15 middle"><?= lang('Comments-n'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'answerall' || $data['sheet'] == 'answerban') { ?> active<?php } ?>">
            <a title="<?= lang('Answers-n'); ?>" href="/admin/answers">
                <i class="icon-comment-empty middle"></i>
                <span class="size-15 middle"><?= lang('Answers-n'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'badges') { ?> active<?php } ?>">
            <a title="<?= lang('Badges'); ?>" href="/admin/badges">
                <i class="icon-award middle"></i>
                <span class="size-15 middle"><?= lang('Badges'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'domains') { ?> active<?php } ?>">
            <a title="<?= lang('Domains'); ?>" href="/admin/webs">
                <i class="icon-link middle"></i>
                <span class="size-15 middle"><?= lang('Domains'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'words') { ?> active<?php } ?>">
            <a title="<?= lang('Stop words'); ?>" href="/admin/words">
                <i class="icon-info middle"></i>
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