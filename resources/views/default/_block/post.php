<?php if (!empty($posts)) { ?> 
    <?php foreach ($posts as  $post) { ?>

    <div class="post-telo white-box">
      <div class="post-header small flex">
        <a class="gray" href="/u/<?= $post['login']; ?>">
            <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
            <span class="indent"></span> 
            <?= $post['login']; ?>
        </a> 
        <span class="indent"></span> 
        <span class="gray lowercase">
           <?= $post['post_date'] ?>
        </span>
      </div>
      
      <?php if($post['post_thumb_img']) { ?>
        <a title="<?= $post['post_title']; ?>" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>"> 
          <?= post_img($post['post_thumb_img'], $post['post_title'],  'thumb no-mob', 'thumbnails'); ?>
        </a>
      <?php } ?>
      
      <div class="post-body">
        <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
          <h2 class="title indent-big"><?= $post['post_title']; ?>
            <?php if ($post['post_is_deleted'] == 1) { ?> 
              <i class="light-icon-trash red"></i>
            <?php } ?>
            <?php if($post['post_closed'] == 1) { ?> 
              <i class="light-icon-lock"></i>
            <?php } ?>
            <?php if($post['post_top'] == 1) { ?> 
              <i class="light-icon-arrow-narrow-up red"></i>
            <?php } ?>
            <?php if($post['post_lo'] > 0) { ?> 
              <i class="light-icon-checks red"></i>
            <?php } ?>
            <?php if($post['post_type'] == 1) { ?> 
              <i class="light-icon-language green"></i>
            <?php } ?>
            <?php if($post['post_translation'] == 1) { ?> 
              <span class="translation small italic lowercase"><?= lang('Translation'); ?></span>
            <?php } ?>
            <?php if($post['post_tl'] > 0) { ?> 
              <span class="trust-level italic small">tl<?= $post['post_tl']; ?></span>
            <?php } ?>
            <?php if($post['post_merged_id'] > 0) { ?> 
              <i class="light-icon-arrow-forward-up red"></i>
            <?php } ?>
          </h2>
        </a>
        <div class="flex indent-big">
            <a class="gray small" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
             <span class="post-space-color" style="background-color: <?= $post['space_color']; ?>;"></span> 
             <?= $post['space_name']; ?> 
            </a> 
            <span class="indent-big"></span> 
            <?= html_topic($post['topic_list'], 'gray small'); ?>
            <?php if($post['post_url_domain']) { ?> 
              <span class="indent"></span> 
              <a class="gray small" href="/domain/<?= $post['post_url_domain']; ?>">
                <i class="light-icon-link small middle"></i> <?= $post['post_url_domain']; ?>
              </a> 
            <?php } ?>
        </div>
        <div class="post-details">
          <div class="show_add_<?= $post['post_id']; ?>">
            <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
              <?= $post['post_content_preview']; ?>
              <span class="s_<?= $post['post_id']; ?> show_detail"></span>
            </div>
          </div>
        </div>

        <?php if($post['post_content_img']) { ?> 
            <div class="post-img">
              <a title="<?= $post['post_title']; ?>" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                <?= post_img($post['post_content_img'], $post['post_title'], 'img-post', 'cover'); ?>
              </a>
            </div>  
        <?php } ?>

        <div class="post-footer lowercase">
          <?= votes($uid['id'], $post, 'post'); ?> 
          <?php if($post['post_answers_count'] !=0) { ?> 
            <a class="right gray" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
              <?php if($post['post_type'] ==0) { ?>
                 <i class="light-icon-messages middle"></i>
                 <?= $post['post_answers_count'] + $post['post_comments_count']; ?> 
              <?php } else { ?>  
                 <i class="light-icon-message middle"></i>              
                 <?= $post['post_answers_count']; ?>  <?= $post['lang_num_answers']; ?>   
              <?php } ?>
            </a>
          <?php } ?> 
        </div>

      </div>            
    </div>
    <?php } ?>

<?php } else { ?>
    <div class="no-content"><?= lang('There are no posts'); ?>...</div>
<?php } ?>