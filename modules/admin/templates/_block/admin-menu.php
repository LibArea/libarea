<aside>
    <div class="logo">
        <a class="light-gray" href="/admin">Admin</a>
    </div>
    <ul class="admin-menu">
        <li class="nav<?php if ($data['sheet'] == 'admin') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Aadmin'); ?>" href="/admin">
                <i class="light-icon-building-pavilon middle"></i>
                <span class="middle"><?= lang('Admin'); ?></span>
            </a>
        <li>
        <li class="nav<?php if ($data['sheet'] == 'userall' || $data['sheet'] == 'banuser') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Users'); ?>" href="/admin/users">
                <i class="light-icon-users middle"></i>
                <span class="middle"><?= lang('Users'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'all' || $data['sheet'] == 'ban') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Audit'); ?>" href="/admin/audits">
                <i class="light-icon-activity middle"></i>
                <span class="middle"><?= lang('Audit'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'allspaces' || $data['sheet'] == 'banspaces') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Spaces'); ?>" href="/admin/spaces">
                <i class="light-icon-infinity middle"></i>
                <span class="middle"><?= lang('Spaces'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'topics') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Topics'); ?>" href="/admin/topics">
                <i class="light-icon-layers-subtract middle"></i>
                <span class="middle"><?= lang('Topics'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'invitations') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Invites'); ?>" href="/admin/invitations">
                <i class="light-icon-wind middle"></i>
                <span class="middle"><?= lang('Invites'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'postall' || $data['sheet'] == 'postban') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Invites'); ?>" href="/admin/posts">
                <i class="light-icon-book middle"></i>
                <span class="middle"><?= lang('Posts'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'commentall' || $data['sheet'] == 'commentban') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Comments-n'); ?>" href="/admin/comments">
                <i class="light-icon-messages middle"></i>
                <span class="middle"><?= lang('Comments-n'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'answerall' || $data['sheet'] == 'answerban') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Answers-n'); ?>" href="/admin/answers">
                <i class="light-icon-message middle"></i>
                <span class="middle"><?= lang('Answers-n'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'badges') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Badges'); ?>" href="/admin/badges">
                <i class="light-icon-award middle"></i>
                <span class="middle"><?= lang('Badges'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'domains') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Domains'); ?>" href="/admin/webs">
                <i class="light-icon-link middle"></i>
                <span class="middle"><?= lang('Domains'); ?></span>
            </a>
        </li>
        <li class="nav<?php if ($data['sheet'] == 'words') { ?> active<?php } ?>">
            <a class="light-gray" title="<?= lang('Stop words'); ?>" href="/admin/words">
                <i class="light-icon-ab-testing middle"></i>
                <span class="middle"><?= lang('Stop words'); ?></span>
            </a>
        </li>
    </ul>
    <hr class="footer">
    <div class="footer small">
        Loriup &copy; <?= date('Y'); ?>
    </div>
</aside>