<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding topic-list">
                <h1><?= $data['h1']; ?></h1>
                
                <?php if (!empty($topics)) { ?>
              
                    <?php foreach ($topics as $topic) { ?>  
                        <div class="item">
                            <a title="<?= $topic['topic_title']; ?>" class="img-topic" href="/topic/<?= $topic['topic_slug']; ?>">
                                <img alt="<?= $topic['topic_title']; ?>" src="<?= topic_url($topic['topic_img'], 'max'); ?>">
                            </a>
                    
                            <div class="item-desc">
                                <a title="<?= $topic['topic_title']; ?>" href="/topic/<?= $topic['topic_slug']; ?>">
                                    <?= $topic['topic_title']; ?>
                                </a>
                                <span class="indent"></span>
                                <sup class="gray">x<?= $topic['topic_count']; ?></sup>
                        
                                <div class="small"><?= $topic['topic_cropped']; ?>...</div>
                            </div>
                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <div class="no-content"><i class="icon info"></i> <?= lang('No topics'); ?></div>
                <?php } ?>
                
            <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], 'topics'); ?>
     
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                 <?= lang('topic_info'); ?>
            </div>
        </div>
        <?php if(!empty($news)) { ?>
            <div class="white-box">
                <div class="inner-padding big"> 
                    <h3 class="style small"><?= lang('New ones'); ?></h3>
                    <?php foreach ($news as $new) { ?>
                        <a title="<?= $new['topic_title']; ?>" class="tags" href="/topic/<?= $new['topic_slug']; ?>">
                            <?= $new['topic_title']; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>        