<aside>
    <div class="white-box menu-info">
        <div class="inner-padding big">
            <div class="menu-info">
                <a class="gray<?php if ($uid['uri'] == '/admin') { ?> active<?php } ?>" title="<?= lang('Aadmin'); ?>" href="/admin">
                    <i class="light-icon-building-pavilon middle"></i>
                    <span class="middle"><?= lang('Admin'); ?></span>
                </a>
                <a class="gray<?php if ($uid['uri'] == '/admin/users' || $uid['uri'] == '/admin/users/ban') { ?> active<?php } ?>" title="<?= lang('Users'); ?>" href="/admin/users">
                    <i class="light-icon-users middle"></i>
                    <span class="middle"><?= lang('Users'); ?></span>
                </a>
                <a class="gray<?php if ($uid['uri'] == '/admin/audit' || $uid['uri'] == '/admin/audit/approved') { ?> active<?php } ?>" title="<?= lang('Audit'); ?>" href="/admin/audit">
                    <i class="light-icon-activity middle"></i>
                    <span class="middle"><?= lang('Audit'); ?></span>
                </a>
                <a class="gray<?php if ($uid['uri'] == '/admin/spaces') { ?> active<?php } ?>" title="<?= lang('Spaces'); ?>" href="/admin/spaces">
                    <i class="light-icon-infinity middle"></i>
                    <span class="middle"><?= lang('Spaces'); ?></span>
                </a>
                <a class="gray<?php if ($data['sheet'] == 'topics') { ?> active<?php } ?>" title="<?= lang('Topics'); ?>" href="/admin/topics">
                    <i class="light-icon-layers-subtract middle"></i>
                    <span class="middle"><?= lang('Topics'); ?></span>
                </a>
                <a class="gray<?php if ($data['sheet'] == 'invitations') { ?> active<?php } ?>" title="<?= lang('Invites'); ?>" href="/admin/invitations">
                    <i class="light-icon-wind middle"></i>
                    <span class="middle"><?= lang('Invites'); ?></span>
                </a>
                <a class="gray<?php if ($data['sheet'] == 'comments') { ?> active<?php } ?>" title="<?= lang('Comments-n'); ?>" href="/admin/comments">
                    <i class="light-icon-messages middle"></i>
                    <span class="middle"><?= lang('Comments-n'); ?></span>
                </a>
                <a class="gray<?php if ($data['sheet'] == 'answers') { ?> active<?php } ?>" title="<?= lang('Answers-n'); ?>" href="/admin/answers">
                    <i class="light-icon-message middle"></i>
                    <span class="middle"><?= lang('Answers-n'); ?></span>
                </a>
                <a class="gray<?php if ($data['sheet'] == 'badges') { ?> active<?php } ?>" title="<?= lang('Badges'); ?>" href="/admin/badges">
                    <i class="light-icon-award middle"></i>
                    <span class="middle"><?= lang('Badges'); ?></span>
                </a>
                <a class="gray<?php if ($data['sheet'] == 'domains') { ?> active<?php } ?>" title="<?= lang('Domains'); ?>" href="/admin/domains">
                    <i class="light-icon-link middle"></i>
                    <span class="middle"><?= lang('Domains'); ?></span>
                </a>
                <a class="gray<?php if ($data['sheet'] == 'words') { ?> active<?php } ?>" title="<?= lang('Stop words'); ?>" href="/admin/words">
                    <i class="light-icon-ab-testing middle"></i>
                    <span class="middle"><?= lang('Stop words'); ?></span>
                </a>
            </div>
        </div> 
    </div>
</aside>