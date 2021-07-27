<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

                <?php if (!empty($answers)) { ?>
              
                    <?php foreach ($answers as $answer) { ?>  
                    
                        <div class="answ-telo_bottom" id="answer_<?= $answer['answer_id']; ?>">
                            <div class="small">
                                <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'ava'); ?>
                                <a class="date" href="/u/<?= $answer['login']; ?>"><?= $answer['login']; ?></a> 
                                <span class="indent"> &#183; </span>
                                <?= $answer['date']; ?>

                                <span class="indent"> &#183; </span>
                                <a href="/post/<?= $answer['post_id']; ?>/<?= $answer['post_slug']; ?>">
                                    <?= $answer['post_title']; ?>
                                </a>
                                
                                <?php if($answer['post_type'] == 1) { ?> 
                                    <i class="light-icon-language green"></i>
                                <?php } ?>
                            </div>
                            <div class="answ-telo-body">
                                <?= $answer['content']; ?> 
                            </div>
                           <div class="content-footer">
                               + <?= $answer['answer_votes']; ?>
                               <span id="cm_dell" class="right comment_link small">
                                    <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action">
                                        <?= lang('Recover'); ?>
                                    </a>
                               </span>
                           </div>
                        </div>
                    <?php } ?>
                    
                <?php } else { ?>
                    <p class="no-content gray">
                        <i class="light-icon-info-square middle"></i> 
                        <span class="middle"><?= lang('No answers'); ?>...</span>
                    </p>
                <?php } ?>

            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/answers'); ?>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>