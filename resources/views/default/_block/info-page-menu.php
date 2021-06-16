<aside> 
    <div class="menu-info">
        <a <?php if($uid['uri'] == '/info') { ?>class="active"<?php } ?> href="/info">~ <?= lang('Info'); ?></a>
        <a <?php if($uid['uri'] == '/info/privacy') { ?>class="active"<?php } ?> href="/info/privacy">~ <?= lang('Privacy'); ?></a>
    </div>
</aside>