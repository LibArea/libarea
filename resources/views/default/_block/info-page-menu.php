<aside> 
    <div class="white-box menu-info">
        <div class="inner-padding big">
            <a title="<?= lang('Info'); ?>" <?php if($uid['uri'] == '/info') { ?>class="active"<?php } ?> href="/info">
                ~ <?= lang('Info'); ?>
            </a>
            <a title="<?= lang('Privacy'); ?>" <?php if($uid['uri'] == '/info/privacy') { ?>class="active"<?php } ?> href="/info/privacy">
                ~ <?= lang('Privacy'); ?>
            </a>
        </div>
    </div>
</aside>