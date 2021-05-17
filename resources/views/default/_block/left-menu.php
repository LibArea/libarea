<nav>

        <div class="bar-m">Меню</div>
        <div class="togglemenuoff">
            <i class="icon size-actual"></i>  
        </div>

    <?php if($uid['id'] > 0) { ?>                     

            <a href="/u/<?= $uid['login']; ?>/favorite">
                <i class="icon star"></i>  
                <span><?= lang('Favorites'); ?></span>              
            </a>

    <?php } ?>  

        <a<?php if($uid['uri'] == '/answers') { ?> class="active"<?php } ?> title="<?= lang('Answers'); ?>" href="/answers">
            <i class="icon action-undo"></i>
            <?= lang('Answers'); ?>
        </a>

        <a<?php if($uid['uri'] == '/comments') { ?> class="active"<?php } ?> title="<?= lang('Comments'); ?>" href="/comments">
            <i class="icon bubbles"></i>
            <?= lang('Comments'); ?>
        </a>

        <a<?php if($uid['uri'] == '/space') { ?> class="active"<?php } ?> title="<?= lang('Space'); ?>" href="/space">
            <i class="icon grid"></i>
            <?= lang('Space'); ?>
        </a>

        <a<?php if($uid['uri'] == '/users') { ?> class="active"<?php } ?> title="<?= lang('Users'); ?>" href="/users">
            <i class="icon people"></i>
            <?= lang('Users'); ?>
        </a>

        <a title="<?= lang('Flow'); ?>" href="/flow">
            <i class="icon energy"></i>
            <?= lang('Flow'); ?>
        </a>

    <?php if(!empty($space_bar)) { ?>

        <div class="bar-space">
            <div class="bar-m bar-title"><?= lang('Signed'); ?></div>  
            <?php foreach ($space_bar as  $sig) { ?>
                <a class="bar-space-telo" href="/s/<?= $sig['space_slug']; ?>" title="<?= $sig['space_name']; ?>">
                    <img src="/uploads/spaces/small/<?= $sig['space_img']; ?>" alt="<?= $sig['space_name']; ?>">
                    <?php if($sig['space_user_id'] == $uid['id']) { ?>
                        <div class="my_space"></div>
                    <?php } ?>
                    <span class="bar-name"><?= $sig['space_name']; ?></span>
                </a>
            <?php } ?>
        </div>    

    <?php }  ?> 
</nav>