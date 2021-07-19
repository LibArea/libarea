<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <?php if (!empty($result)) { ?>
                   <div>Вы искали: <b><?= $query; ?></b></div>
                <?php } ?>
                
                <br>
              
                <?php if (!empty($result)) { ?>
                    
                    <?php if (Lori\Config::get(Lori\Config::PARAM_SEARCH) == 0) { ?>
                        <?php foreach ($result as  $post) { ?>
                            <div class="search max-width">
                                    <a class="search-title" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug'] ?>"><?= $post['post_title']; ?></a> 
                                    <span class="no-md"><?= $post['post_content']; ?>...</span>
                            </div>
                            <div class="vertical-ind"></div>
                        <?php } ?> 
                    <?php } else { ?>
                        <?php foreach ($result as  $post) { ?>
                               <div class="search max-width">
                                    <div class="search-info small">
                                        <?= spase_logo_img($post['space_img'], 'small', $post['space_name'], 'space-img'); ?>
                                        <a class="search-info" href="/s/<?= $post['space_slug']; ?>"><?= $post['space_name']; ?></a>
                                         — <?= lang('Like'); ?> <?= $post['post_votes']; ?>
                                    </div>
                                    <a class="search-title" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug'] ?>"><?= $post['_title']; ?></a><br>
                                    <span class="no-md"><?= $post['_content']; ?></span>
                               </div>
                           <div class="vertical-ind"></div>
                        <?php } ?> 
                    <?php } ?>

                <?php } else { ?>
                    <p>Поиск не дал результатов...<p>
                <?php } ?>
            </div>        
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('info_search'); ?>
            
                <i><?= lang('Under development'); ?></i>
 
            </div>
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 