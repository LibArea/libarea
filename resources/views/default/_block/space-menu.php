<aside id="sidebar"> 
    <div class="menu-info">
        <?php if($uid['id'] > 0) { ?>
            <a href="/s/<?= $space['space_slug']; ?>">
               ~  В пространство
            </a>
            <a <?php if($uid['uri'] == '/space/'.$space['space_slug'].'/edit') { ?>class="active"<?php } ?> href="/u/<?= $uid['login']; ?>/setting">
                ~ <?= lang('Settings'); ?>
            </a> 
            <a href="#">
                ~ <?= lang('Tags'); ?>
            </a>  
        <?php } ?>
    </div>
</aside>