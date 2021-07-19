<?php include TEMPLATE_DIR . '/header.php'; ?>

<?php if(!$uid['id']) { ?>
  <div class="banner">
    <div class="wrap-header">
      <h1><?= Lori\Config::get(Lori\Config::PARAM_BANNER_TITLE); ?></h1>
      <span><?= Lori\Config::get(Lori\Config::PARAM_BANNER_DESC); ?>...</span>
    </div>
  </div>
<?php } ?>

<div class="wrap">
  <main class="telo">
    <ul class="nav-tabs">
      <?php if($data['sheet'] == 'feed') { ?>
        <li class="active">
          <span><?= lang('Feed'); ?></span>
        </li>
        <?php if($uid['id']) { ?>
          <li>  
            <a href="/all">
              <span><?= lang('All'); ?></span>
            </a>
          </li>
        <?php } ?>
        <li>
          <a href="/top">
            <span>Top</span>
          </a>
        </li>
      <?php } elseif($data['sheet'] == 'all') { ?>
        <li>  
          <a href="/">
            <span><?= lang('Feed'); ?></span>
          </a>
        </li>
        <?php if($uid['id']) { ?>
          <li class="active">
            <span><?= lang('All'); ?></span>
          </li>
        <?php } ?>
        <li>
          <a href="/top">
            <span>Top</span>
          </a>
        </li>
      <?php } else { ?>
        <li>  
          <a href="/">
            <span><?= lang('Feed'); ?></span>
          </a>
        </li>
        <?php if($uid['id']) { ?>
          <li>  
            <a href="/all">
              <span><?= lang('All'); ?></span>
            </a>
          </li>
        <?php } ?>        
        <li class="active">
          <span>Top</span>
        </li>
      <?php } ?>    
    </ul>

    <?php if($uid['uri'] == '/' && $uid['id'] > 0 && empty($space_user)) { ?>
        <div class="white-box">
            <div class="inner-padding big center">
                <i class="icon bulb red"></i> <?= lang('space-subscription'); ?>...
            </div>
        </div>
    <?php } ?>

    <?php if (!empty($posts)) { ?> 
    
      <?php foreach ($posts as  $post) { ?>
      
        <div class="post-telo white-box">
          <div class="post-header small">
            <a class="gray" href="/u/<?= $post['login']; ?>">
                <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
                <span class="indent"></span> 
                <?= $post['login']; ?>
            </a> 

            <span class="indent"></span> 
            <a class="gray" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
              <?= $post['space_name']; ?>
            </a> 
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
                  <i class="icon trash"></i>
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
            
            <?= html_topic($post['topic_list'], 'gray small indent-big'); ?>

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
    
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
    
  </main>
  <aside>
    <?php if ($uid['id']) { ?>
      <?php if(!empty($space_user)) { ?>
        <div class="white-box">
          <div class="inner-padding"> 
            <div class="bar-title small">
                <?= lang('Signed'); ?>
                <a class="right" title="<?= lang('Spaces'); ?>" href="/spaces"><i class="icon arrow-right"></i></a>
            </div>  
            <?php foreach ($space_user as  $sig) { ?>
              <a class="bar-space-telo" href="/s/<?= $sig['space_slug']; ?>" title="<?= $sig['space_name']; ?>">
              
                <?= spase_logo_img($sig['space_img'], 'small', $sig['space_name'], 'img-space'); ?>
                
                <span class="bar-name small"><?= $sig['space_name']; ?></span>
                <?php if($sig['space_user_id'] == $uid['id']) { ?>
                  <sup class="red indent">+</sup>
                <?php } ?>
              </a>
            <?php } ?>
          </div> 
        </div>   
      <?php } ?>
    <?php } else { ?>
      <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
    <?php } ?>
    
    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="last-comm white-box sticky"> 
        <div class="inner-padding">
          <?php $num = 1; ?>
          <?php foreach ($data['latest_answers'] as  $answer)  { ?>
            <?php $num++; ?>
            <style nonce="<?= $_SERVER['nonce']; ?>">
             .comm-space-color_<?= $num; ?> {border-left: 2px solid <?= $answer['space_color']; ?>;}
            </style>
            <div class="sb-telo comm-space-color_<?= $num; ?>">
              <div class="sb-date small"> 
                <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'ava'); ?>
                <span class="indent"></span>
                <?= $answer['answer_date']; ?>
              </div> 
              <a href="/post/<?= $answer['post_id']; ?>/<?= $answer['post_slug']; ?>#answer_<?= $answer['answer_id']; ?>">
                <?= $answer['answer_content']; ?>...  
              </a>
             </div>
          <?php } ?>
        </div> 
      </div> 
    <?php } ?>
  </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>