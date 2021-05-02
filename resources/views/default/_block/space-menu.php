<aside class="sidebar"> 
    <div class="menu-info">
        <?php if($uid['id'] > 0) { ?>
            <a href="/s/<?= $space['space_slug']; ?>">
               ~  <?= lang('In space'); ?>
            </a>
            <a <?php if($uid['uri'] == '/space/'.$space['space_slug'].'/edit') { ?>class="active"<?php } ?> href="/space/<?= $space['space_slug']; ?>/edit">
                ~ <?= lang('Settings'); ?>
            </a> 
            <a <?php if($uid['uri'] == '/space/'.$space['space_slug'].'/tags') { ?>class="active"<?php } ?> href="/space/<?= $space['space_slug']; ?>/tags">
                ~ <?= lang('Tags'); ?>
            </a>  
        <?php } ?>
    </div>
</aside>