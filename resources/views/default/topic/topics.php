<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?>
                    <?php if($uid['trust_level'] == 5) { ?>
                        <a class="right" href="/admin/topics"> 
                            <i class="light-icon-edit middle"></i>          
                        </a>
                    <?php } ?>
                </h1>
                
                <?php if (!empty($topics)) { ?>
                    <div class="oblong-box-list topic-box-list">
                    <?php foreach ($topics as $topic) { ?>  
                        <div class="oblong-box">
                            <a title="<?= $topic['topic_title']; ?>" class="img-box" href="/topic/<?= $topic['topic_slug']; ?>">
                                <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'ava-54'); ?>
                            </a>
                            <div class="item-desc">
                                <a title="<?= $topic['topic_title']; ?>" href="/topic/<?= $topic['topic_slug']; ?>">
                                    <?= $topic['topic_title']; ?>
                                </a>

                                <span class="indent"></span>
                                <sup class="gray">x<?= $topic['topic_count']; ?></sup>
                                <?php if ($topic['topic_is_parent'] == 1 && $uid['trust_level'] == 5) { ?>
                                     <sup class="red small">root</sup>
                                <?php } ?>
                                <div class="small"><?= $topic['topic_cropped']; ?>...</div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                <?php } else { ?>
                    <p class="no-content gray">
                        <i class="light-icon-info-square middle"></i> 
                        <span class="middle"><?= lang('Topics no'); ?>...</span>
                    </p>
                <?php } ?>
                
                <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/topics'); ?>
     
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
                        </a><br>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        
        <?php if ($data['sheet'] == 'topics' && $uid['trust_level'] > 4) { ?>
            <a class="right small button" href="/update/count"><?= lang('Update the data'); ?></a> 
        <?php } ?> 
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>        