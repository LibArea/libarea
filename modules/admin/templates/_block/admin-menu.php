<aside>
    <div class="logo size-21">
        <a class="light-gray" href="/admin">Admin</a>
    </div>
    <ul class="p0">
        <li class="nav<?php if ($data['sheet'] == 'admin') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Aadmin'); ?>" href="/admin">
                <i class="icon-building-pavilon middle"></i>
                <span class="middle"><?= lang('Admin'); ?></span>
            </a>
        <li>
        <li class="nav<?php if ($data['sheet'] == 'userall' || $data['sheet'] == 'banuser') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Users'); ?>" href="/admin/users">
                <i class="icon-user-o middle"></i>
                <span class="middle"><?= lang('Users'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'all' || $data['sheet'] == 'ban') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Audit'); ?>" href="/admin/audits">
                <i class="icon-lightbulb middle"></i>
                <span class="middle"><?= lang('Audit'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'allspaces' || $data['sheet'] == 'banspaces') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Spaces'); ?>" href="/admin/spaces">
                <i class="icon-infinity middle"></i>
                <span class="middle"><?= lang('Spaces'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'topics') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Topics'); ?>" href="/admin/topics">
                <i class="icon-clone middle"></i>
                <span class="middle"><?= lang('Topics'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'invitations') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Invites'); ?>" href="/admin/invitations">
                <i class="icon-user-add-outline middle"></i>
                <span class="middle"><?= lang('Invites'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'postall' || $data['sheet'] == 'postban') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Invites'); ?>" href="/admin/posts">
                <i class="icon-book-open middle"></i>
                <span class="middle"><?= lang('Posts'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'commentall' || $data['sheet'] == 'commentban') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Comments-n'); ?>" href="/admin/comments">
                <i class="icon-commenting-o middle"></i>
                <span class="middle"><?= lang('Comments-n'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'answerall' || $data['sheet'] == 'answerban') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Answers-n'); ?>" href="/admin/answers">
                <i class="icon-comment-empty middle"></i>
                <span class="middle"><?= lang('Answers-n'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'badges') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Badges'); ?>" href="/admin/badges">
                <i class="icon-award middle"></i>
                <span class="middle"><?= lang('Badges'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'domains') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Domains'); ?>" href="/admin/webs">
                <i class="icon-link middle"></i>
                <span class="middle"><?= lang('Domains'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'words') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Stop words'); ?>" href="/admin/words">
                <i class="icon-info middle"></i>
                <span class="middle"><?= lang('Stop words'); ?></span>
            </a>
        </li>
    </ul>
     
    <div class="center gray size-13">
        ------------------------
        <br>
        Loriup &copy; <?= date('Y'); ?>
    </div>
</aside>