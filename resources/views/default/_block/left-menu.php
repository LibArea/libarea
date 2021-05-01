<div class="menu">
    <div class="bar-m">Меню</div>
    <div class="togglemenuoff">
        <icon name="size-actual"></icon>  
    </div>
    <?php if($uid['id'] > 0) { ?>                     
        <a href="/u/<?= $uid['login']; ?>/favorite">
            <icon name="star"></icon>  
            <span><?= lang('Favorites'); ?></span>              
        </a>
    <?php } ?>                     
    <a <?php if($uid['uri'] == '/top') { ?>class="active"<?php } ?> title="<?= lang('TOP'); ?>" href="/top">
        <icon name="graph"></icon>
        <span><?= lang('TOP'); ?></span> 
    </a> 
    <a <?php if($uid['uri'] == '/answers') { ?>class="active"<?php } ?> title="<?= lang('Answers'); ?>" href="/answers">
        <icon name="action-undo"></icon>
        <span><?= lang('Answers'); ?></span>
    </a>
    <a <?php if($uid['uri'] == '/comments') { ?>class="active"<?php } ?> title="<?= lang('Comments'); ?>" href="/comments">
        <icon name="bubbles"></icon>
        <span><?= lang('Comments'); ?></span>
    </a>
    <a <?php if($uid['uri'] == '/space') { ?>class="active"<?php } ?> title="<?= lang('Space'); ?>" href="/space">
        <icon name="grid"></icon>
        <span><?= lang('Space'); ?></span>
    </a>
    <a <?php if($uid['uri'] == '/users') { ?>class="active"<?php } ?> title="<?= lang('Users'); ?>" href="/users">
        <icon name="people"></icon>
        <span><?= lang('Users'); ?></span>
    </a>
    <a title="<?= lang('Flow'); ?>" href="/flow">
        <icon name="energy"></icon>
        <span><?= lang('Flow'); ?></span>
    </a>
    
    
    <?php if(!empty($space_bar)) { ?>
        <div class="bar-space">
            <div class="bar-m bar-title"><?= lang('Signed'); ?></div>  
            <?php foreach ($space_bar as  $sig) { ?>
                <a class="bar-space-telo" href="/s/<?= $sig['space_slug']; ?>" title="<?= $sig['space_name']; ?>">
                    <img src="/uploads/space/small/<?= $sig['space_img']; ?>" alt="<?= $sig['space_name']; ?>">
                    <span class="bar-name"><?= $sig['space_name']; ?></span>
                </a>
            <?php } ?>
        </div>    
    <?php }  ?>    
</div>