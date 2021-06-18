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
                <a <?php if( $uid['uri'] == '/admin/badges') { ?> class="active"<?php } ?> title="<?= lang('Badges'); ?>" href="/admin/badges">
                    ~ <?= lang('Badges'); ?>
                </a>
            </div>
        </div>    
    </div>
</aside>