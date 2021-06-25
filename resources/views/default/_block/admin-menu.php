<aside>
    <div class="white-box menu-info">
        <div class="inner-padding big">
            <div class="menu-info">
                <a <?php if( $uid['uri'] == '/admin/spaces') { ?> class="active"<?php } ?> title="<?= lang('Spaces'); ?>" href="/admin/spaces">
                    ~ <?= lang('Spaces'); ?>
                </a>
                <a <?php if( $uid['uri'] == '/admin/invitations') { ?> class="active"<?php } ?> title="<?= lang('Invites'); ?>" href="/admin/invitations">
                    ~ <?= lang('Invites'); ?>
                </a>
                <a <?php if( $uid['uri'] == '/admin/comments') { ?> class="active"<?php } ?> title="<?= lang('Comments-n'); ?>" href="/admin/comments">
                    ~ <?= lang('Comments-n'); ?>
                </a>
                <a <?php if( $uid['uri'] == '/admin/answers') { ?> class="active"<?php } ?> title="<?= lang('Answers-n'); ?>" href="/admin/answers">
                    ~ <?= lang('Answers-n'); ?>
                </a>
                <a <?php if( $uid['uri'] == '/admin/badges') { ?> class="active"<?php } ?> title="<?= lang('Badges'); ?>" href="/admin/badges">
                    ~ <?= lang('Badges'); ?>
                </a>
                <a <?php if( $uid['uri'] == '/admin/domains') { ?> class="active"<?php } ?> title="<?= lang('Domains'); ?>" href="/admin/domains">
                    ~ <?= lang('Domains'); ?>
                </a>
                <a <?php if( $uid['uri'] == '/admin/words') { ?> class="active"<?php } ?> title="<?= lang('Stop words'); ?>" href="/admin/words">
                    ~ <?= lang('Stop words'); ?>
                </a>
            </div>
        </div>    
    </div>
</aside>