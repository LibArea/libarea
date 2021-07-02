<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding topic-list">
                <h1><?= $data['h1']; ?></h1>
                
                <?php if (!empty($topics)) { ?>
              
                    <?php foreach ($topics as $topic) { ?>  
                        <div class="item">
                            <a class="img-topic" href="/topic/<?= $topic['topic_slug']; ?>" data-id="2">
                                <img alt="<?= $topic['topic_title']; ?>" class="" src="<?= topic_url($topic['topic_img'], 'max'); ?>">
                            </a>
                    
                            <div class="item-desc">
                                <a href="/topic/<?= $topic['topic_slug']; ?>"><?= $topic['topic_title']; ?></a>
                                <span class="indent"></span>
                                <sup class="gray">x<?= $topic['topic_count']; ?></sup>
                        
                                <div class="small"><?= $topic['topic_cropped']; ?>...</div>
                            </div>
                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <div class="no-content"><i class="icon info"></i> <?= lang('No topics'); ?></div>
                <?php } ?>
                
                <?php if(!($data['pNum'] > $data['pagesCount'])) { ?>
                    <div class="pagination">   
                        <?php if ($data['pNum'] != 1) { ?> 
                            <?php if (($data['pNum'] - 1) == 1) { ?>
                                <a class="link" href="/topics"> 
                                    << <?= lang('Page'); ?> 
                                    <?= $data['pNum'] - 1; ?>
                                </a> 
                            <?php } else { ?>
                                <a class="link" href="/topic/page/<?= $data['pNum'] - 1; ?>"> 
                                    << <?= lang('Page'); ?> 
                                    <?= $data['pNum'] - 1; ?>
                                </a> 
                            <?php } ?>
                        <?php } ?>
                        <?php if($data['pagesCount'] != $data['pNum'] && $data['pNum'] != 1) { ?>|<?php } ?> 
                        <?php if($data['pagesCount'] > $data['pNum']) { ?>
                            <a class="link" href="/topic/page/<?= $data['pNum'] + 1; ?>"><?= lang('Page'); ?>  <?= $data['pNum'] + 1; ?> >></a> 
                        <?php } ?>
                    </div>
                <?php } ?>
                
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
                        <div class="related-box">
                            <a class="tags" href="/topic/<?= $new['topic_slug']; ?>">
                                <?= $new['topic_title']; ?>
                            </a>
                       </div> 
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>        