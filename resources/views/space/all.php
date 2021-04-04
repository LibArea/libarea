<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <div class="left-ots">
        <h1><?= $data['h1']; ?></h1>
        <p>Cимвол (~) используется для обозначения разделов сайта: ~ cms, ~ флуд, ~ вопросы и т.д.</p>
        
        <?php if (!empty($space)) { ?>
      
            <?php foreach ($space as  $sp) { ?>  
                <div class="space-telo">
                    <span class="date"> 
                     ~  <a title="<?= $sp['space_name']; ?>" class="space space_<?= $sp['space_tip']; ?>" href="/s/<?= $sp['space_slug']; ?>">
                            <?= $sp['space_name']; ?>
                        </a> 
                    </span> 
                    <span class="date space-des">  
                        <?= $sp['space_description']; ?>    
                    </span>
                </div>
            <?php } ?>

        <?php } else { ?>

            <h3>Нет тегов</h3>

            <p>К сожалению тегов нет...</p>

        <?php } ?>
        
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>        