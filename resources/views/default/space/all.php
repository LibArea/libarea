<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/_block/left-menu.php'; ?>
<main>
    <h1><?= $data['h1']; ?></h1>

    <?php if (!empty($space)) { ?>
  
        <?php foreach ($space as  $sp) { ?>  
            <div class="space-telo">
            
                <img class="space-img" alt="<?= $sp['space_name']; ?>" src="/uploads/space/<?= $sp['space_img'] ?>">
            
                <span class="space-name"> 
                    <div class="space-color space_<?= $sp['space_color'] ?>"></div>
                    <a title="<?= $sp['space_name']; ?>" class="space-s" href="/s/<?= $sp['space_slug']; ?>">
                        <?= $sp['space_name']; ?>
                    </a> 
                </span> 
                <?php if($sp['space_type'] == 1) { ?>
                     <small><span class="red">— <?= lang('official'); ?></span></small> 
                <?php } ?>

                <?php if(!$uid['id']) { ?> 
                    <div class="right"> 
                        <a href="/login"><div class="hide-space-id add-space"><?= lang('Read'); ?></div></a>
                    </div>
                <?php } else { ?>
                    <?php if($sp['space_id'] !=1) { ?>
                        <?php if($sp['space_user_id'] != $uid['id']) { ?>
                            <div class="right"> 
                                <?php if($sp['signed_space_id'] >= 1) { ?>
                                    <div data-id="<?= $sp['space_id']; ?>" class="hide-space-id no-space">
                                        <?= lang('Unsubscribe'); ?>
                                    </div>
                                <?php } else { ?> 
                                    <div data-id="<?= $sp['space_id']; ?>" class="hide-space-id add-space">
                                        <?= lang('Read'); ?>
                                    </div>
                                <?php } ?>   
                            </div> 
                        <?php } ?>    
                    <?php } ?>                        
                <?php } ?> 

                <div class="space-des all">
                    <?php if($sp['space_description']) { ?> 
                        <?= $sp['space_description']; ?> 
                    <?php } else { ?> 
                        Описание формируется
                    <?php } ?>    
                </div>

            </div>
        <?php } ?>

    <?php } else { ?>

        <h3>Нет тегов</h3>

        <p>К сожалению тегов нет...</p>

    <?php } ?>

</main>
<?php if($uid['trust_level'] >= $GLOBALS['conf']['space']) { ?>
    <?php if($count_space <= 2) { ?>
        <aside id="sidebar"> 
            <div class="right">
                <a class="add-space" href="/space/add">+ <?= lang('To create'); ?></a>
            </div>    
        </aside>
    <?php } ?> 
<?php } ?> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>        