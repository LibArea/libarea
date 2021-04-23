<div class="menu">
    <div class="bar-m">Меню</div>
    <div class="togglemenuoff">
        <svg class="md-icon moon"><use xlink:href="/assets/svg/icons.svg#closed"></use></svg>
    </div>
    <?php if($uid['id'] > 0) { ?>                     
        <a href="/u/<?= $uid['login']; ?>/favorite">
            <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#bookmark"></use></svg>  
            <span><?= lang('Favorites'); ?></span>              
        </a>
    <?php } ?>                     
    <a <?php if($uid['uri'] == '/top') { ?>class="active"<?php } ?> title="<?= lang('TOP'); ?>" href="/top">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#bulb"></use></svg>
        <span><?= lang('TOP'); ?></span> 
    </a>
    <a <?php if($uid['uri'] == '/comments') { ?>class="active"<?php } ?> title="<?= lang('Comments'); ?>" href="/comments">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#message"></use></svg>
        <span><?= lang('Comments'); ?></span>
    </a>
    <a <?php if($uid['uri'] == '/space') { ?>class="active"<?php } ?> title="<?= lang('Space'); ?>" href="/space">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#sun"></use></svg>
        <span><?= lang('Space'); ?></span>
    </a>
    <a <?php if($uid['uri'] == '/users') { ?>class="active"<?php } ?> title="<?= lang('Users'); ?>" href="/users">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#users"></use></svg>
        <span><?= lang('Users'); ?></span>
    </a>
    <a title="<?= lang('Flow'); ?>" href="/flow">
        <svg class="md-icon"><use xlink:href="/assets/svg/icons.svg#chat"></use></svg>
        <span><?= lang('Flow'); ?></span>
    </a>
</div>