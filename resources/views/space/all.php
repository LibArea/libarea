<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">
        <div class="telo detail">
            <h1><?= $data['title']; ?></h1>
            <p>Cимвол (∞) используется для обозначения разделов сайта: ∞ cms, ∞ флуд, ∞ вопросы и т.д.</p>
            <div class="telo space">
                <?php if (!empty($data['space'])) { ?>
              
                    <?php foreach ($data['space'] as  $space) { ?>  
                        <div class="space-telo">
                            <span class="date"> 
                             ∞  <a title="<?= $space['space_name']; ?>" class="space space_<?= $space['space_tip']; ?>" href="/s/<?= $space['space_slug']; ?>">
                                    <?= $space['space_name']; ?>
                                </a> 
                            </span> 
                            <span class="date space-des">  
                                <?= $space['space_description']; ?>    
                            </span>
                        </div>
                    <?php } ?>

                <?php } else { ?>

                    <h3>Нет тегов</h3>

                    <p>К сожалению тегов нет...</p>

                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>        