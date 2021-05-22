<?php if($uid['uri'] == '/space') { ?>
    <?= lang('info_space'); ?>
<?php } ?>

<?php if($uid['uri'] == '/flow') { ?>
    <?= lang('info_flow'); ?>
<?php } ?>

<?php if($uid['uri'] == '/users') { ?>
    <?= lang('info_users'); ?>
<?php } ?>

<?php if($uid['uri'] == '/search') { ?>
    <?= lang('info_search'); ?>
<?php } ?>

<?php if (isset($post['post_id']) && $uid['uri'] != '/') { ?> 
    <div class="space-info"> 
        <div class="space-info-img">
            <img class="img-space" alt="<?= $post['space_slug']; ?>" src="/uploads/spaces/logos/<?= $post['space_img']; ?>">
            <a class="space-info-title" href="/s/<?= $post['space_slug']; ?>"><?= $post['space_name']; ?></a> 
        </div>    
        <div class="space-info-desc"><?= $post['space_description']; ?></div> 
    </div>
    
    <?php if($recommend) { ?> 
        <div>
            <h3 class="recommend"><?= lang('Recommended'); ?></h3>  
            <?php $n=0; foreach ($recommend as  $post) { $n++; ?>
                 <div class="l-rec-small"> 
                    <div class="l-rec">0<?= $n; ?></div> 
                    <div class="l-rec-telo"> 
                        <a class="edit-bl"  href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                            <?= $post['post_title']; ?>  
                        </a>
                         
                            <?php if($post['post_answers_num'] !=0) { ?>
                                <span class="n-comm">+<?= $post['post_answers_num'] ?></span>
                            <?php } ?> 
                       
                    </div>
               </div>
            <?php } ?> 
        </div> 
    <?php } ?>  

<?php } ?>