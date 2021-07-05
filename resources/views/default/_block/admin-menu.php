<aside>
    <div class="white-box menu-info">
        <div class="inner-padding big">
            <div class="menu-info">
                <a <?php if ($uid['uri'] == '/admin/spaces') { ?> class="active"<?php } ?> title="<?= lang('Spaces'); ?>" href="/admin/spaces">
                    ~ <?= lang('Spaces'); ?>
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
                <a <?php if ($data['sheet'] == 'topics') { ?> class="active"<?php } ?> title="<?= lang('Topics'); ?>" href="/admin/topics">
                    ~ <?= lang('Topics'); ?>
                </a>
            </div>
        </div> 
    </div>
        <?php if ($data['sheet'] == 'topics') { ?>
            <a class="right small button" href="/admin/update/count"><?= lang('Update the data'); ?></a> 
        <?php } ?> 
</aside>