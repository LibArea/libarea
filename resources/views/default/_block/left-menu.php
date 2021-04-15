<menu> 
    <div class="menu-m">Меню</div>
    <div class="closed-off" id="togglemenu">
        <svg class="md-icon moon"><use xlink:href="/assets/svg/icons.svg#closed"></use></svg>
    </div>
    <a class="menu<?php if(Request::getUri() == '/top') { ?> active<?php } ?>" title="<?= lang('TOP'); ?>" class="home" href="/top">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#bulb"></use></svg>
        <span><?= lang('TOP'); ?></span> 
    </a>
    <a class="menu<?php if(Request::getUri() == '/comments') { ?> active<?php } ?>" title="<?= lang('Comments'); ?>" class="comments" href="/comments">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#message"></use></svg>
        <span><?= lang('Comments'); ?></span>
    </a>
    <a class="menu<?php if(Request::getUri() == '/space') { ?> active<?php } ?>" title="<?= lang('Space'); ?>" href="/space">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#sun"></use></svg>
        <span><?= lang('Space'); ?></span>
    </a>
    <a class="menu<?php if(Request::getUri() == '/users') { ?> active<?php } ?>" title="<?= lang('Users'); ?>" href="/users">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#users"></use></svg>
        <span><?= lang('Users'); ?></span>
    </a>
    <a class="menu<?php if(Request::getUri() == '/flow') { ?> active<?php } ?>" title="<?= lang('Flow'); ?>" href="/flow">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#chat"></use></svg>
        <span><?= lang('Flow'); ?></span>
    </a>
    <a class="menu<?php if(Request::getUri() == '/info') { ?> active<?php } ?>" title="<?= lang('Help'); ?>" href="/info">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#info"></use></svg>
        <?= lang('Help'); ?>
    </a>
</menu>