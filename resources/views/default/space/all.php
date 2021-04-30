<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/_block/left-menu.php'; ?>
<main>
    <h1><?= $data['h1']; ?></h1>

    <?php if (!empty($space)) { ?>
  
        <?php foreach ($space as  $sp) { ?>  
            <div class="space-telo">
                <span class="date"> 
                    <div class="space-color space_<?= $sp['space_color'] ?>"></div>
                    <a title="<?= $sp['space_name']; ?>" class="space-s" href="/s/<?= $sp['space_slug']; ?>">
                        <?= $sp['space_name']; ?>
                    </a> 
                </span> 
                <?php if($sp['space_type'] == 1) { ?>
                     <sup class="red">офф</sup>
                <?php } ?>
                <?php if($sp['hidden_space_id'] >= 1) {  ?><span class="red">&#10003;</span><?php } ?>
                
                
                <span class="date space-des">
                    <?php if($sp['space_description']) { ?> 
                        — &nbsp; <?= $sp['space_description']; ?> 
                    <?php } else { ?> 
                        — &nbsp; описание формируется
                    <?php } ?>    
                </span>
                
                
            </div>
        <?php } ?>

    <?php } else { ?>

        <h3>Нет тегов</h3>

        <p>К сожалению тегов нет...</p>

    <?php } ?>
    
    <p><span class="red">&#10003;</span> говорит о том, что вы не видите посты из этих пространства в ленте. Чтобы сделать их видимыми достаточно зайти в пространство и нажать «Подписаться».</p>
    
</main>
<?php if($uid['trust_level'] >= $GLOBALS['conf']['space']) { ?>
    <aside id="sidebar"> 
        <div class="right">
            <a class="add-space" href="/space/add">+ <?= lang('To create'); ?></a>
        </div>    
    </aside>
<?php } ?> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>        