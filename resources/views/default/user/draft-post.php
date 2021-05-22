<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <h1><?= $data['h1']; ?></h1>

    <div class="drafts max-width">
        <?php if (!empty($drafts)) { ?>
      
            <?php $counter = 0; foreach ($drafts as $dr) { $counter++; ?> 
                    <div class="voters-fav">
                       <div class="score"><?= $counter; ?>.</div> 
                    </div>
                    <div class="post-telo">
                        <a class="u-url" href="/post/<?= $dr['post_id']; ?>/<?= $dr['post_slug']; ?>">
                            <h3 class="title"><?= $dr['post_title']; ?></h3>
                        </a>
                        <div class="footer">
                            <span class="date"> 
                                <?= $dr['post_date']; ?> | 
                                <a href="/post/edit/<?= $dr['post_id']; ?>"><?= lang('Edit'); ?></a>
                            </span>
                        </div>  
                    </div>
            <?php } ?>

        <?php } else { ?>

            <p>Черновиков нет...</p>
            <br>
        <?php } ?>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 