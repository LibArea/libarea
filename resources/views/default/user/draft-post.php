<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <div class="drafts max-width">
                    <?php if (!empty($drafts)) { ?>
                  
                        <?php $counter = 0; foreach ($drafts as $dr) { $counter++; ?> 
                                <div class="voters-fav">
                                   <div class="score"><?= $counter; ?>.</div> 
                                </div>
                                <div class="post-telo">
                                    <a href="/post/<?= $dr['post_id']; ?>/<?= $dr['post_slug']; ?>">
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

                        <p><?= lang('There no drafts'); ?>...</p>
                        <br>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('Under development'); ?>...
            </div>
        </div>   
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 