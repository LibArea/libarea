<aside>
    <div class="white-box menu-info">
        <div class="inner-padding big">
            <div class="menu-info">
                <a <?php if ($uid['uri'] == '/admin') { ?> class="active"<?php } ?> title="<?= lang('Aadmin'); ?>" href="/admin">
                    ~ <?= lang('Admin'); ?>
                </a>
                <a <?php if ($uid['uri'] == '/admin/users' || $uid['uri'] == '/admin/users/ban') { ?> class="active"<?php } ?> title="<?= lang('Users'); ?>" href="/admin/users">
                    ~ <?= lang('Users'); ?>
                </a>
                <a <?php if ($uid['uri'] == '/admin/audit' || $uid['uri'] == '/admin/audit/approved') { ?> class="active"<?php } ?> title="<?= lang('Audit'); ?>" href="/admin/audit">
                    ~ <?= lang('Audit'); ?>
                </a>
                <a <?php if ($uid['uri'] == '/admin/spaces') { ?> class="active"<?php } ?> title="<?= lang('Spaces'); ?>" href="/admin/spaces">
                    ~ <?= lang('Spaces'); ?>
                </a>
                <a <?php if ($data['sheet'] == 'topics') { ?> class="active"<?php } ?> title="<?= lang('Topics'); ?>" href="/admin/topics">
                    ~ <?= lang('Topics'); ?>
                </a>
                <a <?php if ($data['sheet'] == 'invitations') { ?> class="active"<?php } ?> title="<?= lang('Invites'); ?>" href="/admin/invitations">
                    ~ <?= lang('Invites'); ?>
                </a>
                <a <?php if ($data['sheet'] == 'comments') { ?> class="active"<?php } ?> title="<?= lang('Comments-n'); ?>" href="/admin/comments">
                    ~ <?= lang('Comments-n'); ?>
                </a>
                <a <?php if ($data['sheet'] == 'answers') { ?> class="active"<?php } ?> title="<?= lang('Answers-n'); ?>" href="/admin/answers">
                    ~ <?= lang('Answers-n'); ?>
                </a>
                <a <?php if ($data['sheet'] == 'badges') { ?> class="active"<?php } ?> title="<?= lang('Badges'); ?>" href="/admin/badges">
                    ~ <?= lang('Badges'); ?>
                </a>
                <a <?php if ($data['sheet'] == 'domains') { ?> class="active"<?php } ?> title="<?= lang('Domains'); ?>" href="/admin/domains">
                    ~ <?= lang('Domains'); ?>
                </a>
                <a <?php if ($data['sheet'] == 'words') { ?> class="active"<?php } ?> title="<?= lang('Stop words'); ?>" href="/admin/words">
                    ~ <?= lang('Stop words'); ?>
                </a>
            </div>
        </div> 
    </div>
</aside>