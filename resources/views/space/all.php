<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h1><?php echo $data['title']; ?></h1>

        <div class="telo space">
            <?php if (!empty($data['space'])) { ?>
          
                <?php foreach ($data['space'] as  $space) { ?>  
                    <div class="space-telo">
                        <span class="date"> 
                            <a title="<?php echo $space['spcae_name']; ?>" class="space space_<?php echo $space['space_tip']; ?>" href="/s/<?php echo $space['space_slug']; ?>">
                                <?php echo $space['space_name']; ?>
                            </a> 
                        </span> 
                        <span class="date space-des">  
                            <?php echo $space['space_description']; ?>    
                        </span>
                    </div>
                <?php } ?>

            <?php } else { ?>

                <h3>Нет тегов</h3>

                <p>К сожалению тегов нет...</p>

            <?php } ?>
        </div>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>        