<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <a class="tel-topics" title="<?= lang('All topics'); ?>" href="/topics"> ← <?= lang('Topics'); ?></a>
                
                <h1 class="topics"><a href="/topic/<?= $topic['topic_slug']; ?>"><?= $data['h1']; ?></a>
                    <?php if($uid['trust_level'] == 5) { ?>
                        <a class="right" href="/admin/topic/<?= $topic['topic_id']; ?>/edit"> 
                            <i class="icon pencil"></i>          
                        </a>
                    <?php } ?></h1>
                <h3>Введение</h3>               
               
                <?= $topic['topic_info']; ?>
            </div>
        </div>
        
        <?php if(!empty($post_related)) { ?>
            <div class="white-box">
                <div class="inner-padding">
                    <div class="related"> 
                        <h3 class="style small"><?= lang('Related'); ?>:</h3>
                        <?php $num = 0; ?>
                        <?php foreach ($post_related as $related) { ?>
                            <div class="related-box">
                                <?php $num++; ?>
                                <span><?= $num; ?></span>
                                <a href="/post/<?= $related['post_id']; ?>/<?= $related['post_slug']; ?>">
                                    <?= $related['post_title']; ?>
                                </a>
                           </div> 
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <center>
                    <a title="<?= $topic['topic_title']; ?>" href="/topic/<?= $topic['topic_slug']; ?>">
                        <div><?= $topic['topic_title']; ?><div>
                        <img alt="<?= $topic['topic_title']; ?>" src="<?= topic_url($topic['topic_img'], 'max'); ?>">
                    </a>    
                </center>
                <hr>
                <div class="sb-created">
                    <i class="icon calendar"></i> <?= $topic['topic_add_date']; ?> 
                </div>
            </div>
        </div>
        
         
        <?php if(!empty($topic_related)) { ?>
            <div class="white-box">
                <div class="inner-padding big"> 
                    <h3 class="style small"><?= lang('Related'); ?></h3>
                    <?php foreach ($topic_related as $related) { ?>
                        <div class="related-box">
                            <a class="tags" href="/topic/<?= $related['topic_slug']; ?>">
                                <?= $related['topic_title']; ?>
                            </a>
                       </div> 
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
           
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>        