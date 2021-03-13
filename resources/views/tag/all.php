<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h1><?php echo $data['title']; ?></h1>

        <div class="telo tags">
            <?php if (!empty($data['tags'])) { ?>
          
                <?php foreach ($data['tags'] as  $tag) { ?>  
                    <div class="tag-telo">
                        <span class="date"> 
                            <a title="<?php echo $tag['tags_name']; ?>" class="tag tag_<?php echo $tag['tags_tip']; ?>" href="/t/<?php echo $tag['tags_slug']; ?>">
                                <?php echo $tag['tags_name']; ?>
                            </a> 
                        </span> 
                        <span class="date tag-des">  
                            <?php echo $tag['tags_description']; ?>    
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