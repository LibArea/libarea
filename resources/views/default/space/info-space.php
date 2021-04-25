<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100 max-width">
    <span class="right">
        <a href="/space/<?= $space['space_slug']; ?>/tags/add">
             <?= lang('Add'); ?>
        </a>
    </span>
    <h1><?= $data['h1']; ?></h1>
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
<?php include TEMPLATE_DIR . '/_block/space-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>