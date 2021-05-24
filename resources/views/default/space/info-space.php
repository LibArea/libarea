<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <ul class="nav-tabs">
        <li>
           <a href="/space/<?= $space['space_slug']; ?>/edit">
                <span><?= lang('Edit'); ?> - <?= $space['space_slug']; ?></span>
            </a>
        </li>
        <li>
            <a href="/space/<?= $space['space_slug']; ?>/edit/logo">
                <span><?= lang('Logo'); ?> / <?= lang('Cover art'); ?></span>
            </a>
        </li>
        <li class="active">
            <span><?= lang('Tags'); ?></span>
        </li>
        <li class="right">
             <a href="/space/<?= $space['space_slug']; ?>/tags/add">
                <span><?= lang('Add'); ?></span>
            </a>
        </li>
    </ul>

    <div class="telo space">
        <?php if (!empty($tags)) { ?>
            <div class="tags">
                <h3 class="menu-m"><?= lang('Tags'); ?></h3>
                <?php foreach ($tags as  $tag) { ?> 
                    <div>                
                        <a href="/s/<?= $space['space_slug']; ?>/<?= $tag['st_id']; ?>">
                            <?= $tag['st_title']; ?> 
                        </a>
                        <?php if($tag['st_description']) { ?>                            
                          <br> <?= $tag['st_description']; ?> 
                        <?php } ?>                        
                        
                        <a href="/s/<?= $space['space_slug']; ?>/<?= $tag['st_id']; ?>/edit">
                           <small>- <?= lang('Edit'); ?></small>
                        </a>
                        <br> <br> 
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?> 
             Меток пока нет. 
             <a href="/space/<?= $space['space_slug']; ?>/tags/add">
             <?= lang('Add'); ?> <?= lang('Tags'); ?>
             </a>...
        <?php } ?> 
    </div> 
</main>
<aside>
    <?= lang('info_space_tags'); ?>
    <br><br>
    <?= lang('info_space_tags_2'); ?>
</aside>
<?php include TEMPLATE_DIR . '/footer.php'; ?>