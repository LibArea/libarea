<?php if (!empty($posts)) { ?> 
    <?php foreach ($posts as  $post) { ?>

    <div class="post-telo white-box">
      <div class="post-header small">
        <a class="gray" href="/u/<?= $post['login']; ?>">
            <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
            <span class="indent"></span> 
            <?= $post['login']; ?>
        </a> 
        
        <?php if (!empty($post['space_name'])) { ?>
            <span class="indent"></span> 
            <a class="gray" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
              <?= $post['space_name']; ?>
            </a> 
        <?php } ?>
        
        <span class="indent gray">
           <?= $post['post_date'] ?>
        </span>
      </div>
      
      <?php if($post['post_thumb_img']) { ?>
        <a title="<?= $post['post_title']; ?>" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">    
          <img class="thumb no-mob" alt="<?= $post['post_title']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
        </a>
      <?php } ?>
      
      <div class="post-body">
        <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
          <h2 class="title"><?= $post['post_title']; ?>
            <?php if ($post['post_is_deleted'] == 1) { ?> 
              <i class="icon trash red"></i>
            <?php } ?>
            <?php if($post['post_closed'] == 1) { ?> 
              <i class="icon lock"></i>
            <?php } ?>
            <?php if($post['post_top'] == 1) { ?> 
              <i class="icon pin red"></i>
            <?php } ?>
            <?php if($post['post_lo'] > 0) { ?> 
              <i class="icon trophy red"></i>
            <?php } ?>
            <?php if($post['post_type'] == 1) { ?> 
              <i class="icon question green"></i>
            <?php } ?>
            <?php if($post['post_translation'] == 1) { ?> 
              <span class="translation small lowercase"><?= lang('Translation'); ?></span>
            <?php } ?>
            <?php if($post['post_tl'] > 0) { ?> 
              <span class="trust-level small">tl<?= $post['post_tl']; ?></span>
            <?php } ?>
            <?php if($post['post_merged_id'] > 0) { ?> 
              <i class="icon graph red"></i>
            <?php } ?>
          </h2>
        </a>
        
        <?php if($post['post_url_domain']) { ?> 
          <a class="gray small indent-big" href="/domain/<?= $post['post_url_domain']; ?>">
            <i class="icon link"></i> <?= $post['post_url_domain']; ?>
          </a> 
        <?php } ?>
        
        <?php if (!empty($post['topic_list'])) { ?>
            <?= html_topic($post['topic_list'], 'gray small indent-big'); ?>
        <?php } ?>

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
                <?= post_cover_img($post['post_content_img'], $post['post_title'], 'img-post'); ?>
              </a>
            </div>  
        <?php } ?>

        <div class="post-footer lowercase">
          <?= votes($uid['id'], $post, 'post'); ?> 
      
          <?php if($post['post_answers_count'] !=0) { ?> 
            <a class="right gray" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
              <?php if($post['post_type'] ==0) { ?>
                 <i class="icon bubbles"></i> 
                 <?= $post['post_answers_count'] + $post['post_comments_count']; ?> 
              <?php } else { ?>    
                 <i class="icon bubbles"></i> 
                 <?= $post['post_answers_count']; ?>  <?= $post['lang_num_answers']; ?>   
              <?php } ?>
            </a>
          <?php } ?> 
        </div>

      </div>            
    </div>
    <?php } ?>

<?php } else { ?>
    <div class="no-content"><?= lang('no-post'); ?>...</div>
<?php } ?>