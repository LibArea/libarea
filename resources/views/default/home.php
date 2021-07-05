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

    <?php if (!empty($posts)) { ?> 
    
      <?php foreach ($posts as  $post) { ?>
      
        <div class="post-telo white-box">
          <div class="post-header small">
            <img class="ava" alt="<?= $post['login']; ?>" src="<?= user_avatar_url($post['avatar'], 'small'); ?>">
            <span class="indent"></span> 
            <span class="user"> 
              <a href="/u/<?= $post['login']; ?>">
                <?= $post['login']; ?>
              </a> 
            </span>
            <span class="indent"></span> 
            <a href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
              <?= $post['space_name']; ?>
            </a> 
            <span class="indent"></span> 
              <span class="date"> 
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
                <?php if ($post['post_is_delete'] == 1) { ?> 
                  <i class="icon trash"></i>
                <?php } ?>
                <?php if($post['post_closed'] == 1) { ?> 
                  <i class="icon lock"></i>
                <?php } ?>
                <?php if($post['post_top'] == 1) { ?> 
                  <i class="icon pin red"></i>
                <?php } ?>
                <?php if($post['post_lo'] > 0) { ?> 
                  <i class="icon trophy lo"></i>
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
              <a class="date small indent-bid" href="/domain/<?= $post['post_url_domain']; ?>">
                <i class="icon link"></i> <?= $post['post_url_domain']; ?>
              </a> 
            <?php } ?>
            
            <?= html_topic($post['topic_list'], 'date small indent-bid'); ?>

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
                    <img class="img-post" alt="<?= $post['post_title']; ?>" src="/uploads/posts/<?= $post['post_content_img']; ?>">
                  </a>
                </div>  
            <?php } ?>

            <div class="post-footer lowercase">
              <?php if (!$uid['id']) { ?> 
                <div id="vot<?= $post['post_id']; ?>" class="voters">
                  <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                  <div class="score">
                    <?= $post['post_votes'] ? '+'.$post['post_votes'] : $post['post_votes']; ?>
                  </div>
                </div>
              <?php } else { ?> 
                <?php if ($post['votes_post_user_id'] || $uid['id'] == $post['post_user_id']) { ?>
                  <div class="voters active">
                    <div class="up-id"></div>
                    <div class="score">
                      <?= $post['post_votes'] ? '+'.$post['post_votes'] : $post['post_votes']; ?>
                    </div>
                  </div>
                <?php } else { ?>
                  <div id="up<?= $post['post_id']; ?>" class="voters">
                    <div data-id="<?= $post['post_id']; ?>" data-type="post" class="up-id"></div>
                    <div class="score">
                      <?= $post['post_votes'] ? '+'.$post['post_votes'] : $post['post_votes']; ?>
                    </div>
                  </div>
                <?php } ?> 
              <?php } ?> 
          
              <?php if($post['post_answers_num'] !=0) { ?> 
                <a class="right" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                  <?php if($post['post_type'] ==0) { ?>
                     <i class="icon bubbles"></i> 
                     <?= $post['post_answers_num'] + $post['post_comments_num']; ?> 
                  <?php } else { ?>    
                     <i class="icon bubbles"></i> 
                     <?= $post['post_answers_num']; ?>  <?= $post['lang_num_answers']; ?>   
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
      <?php if(!empty($space_bar)) { ?>
        <div class="white-box">
          <div class="inner-padding"> 
            <div class="bar-title small">
                <?= lang('Signed'); ?>
                <a class="right" title="<?= lang('Spaces'); ?>" href="/spaces"><i class="icon arrow-right"></i></a>
            </div>  
            <?php foreach ($space_bar as  $sig) { ?>
              <a class="bar-space-telo" href="/s/<?= $sig['space_slug']; ?>" title="<?= $sig['space_name']; ?>">
                <img src="<?= spase_logo_url($sig['space_img'], 'small'); ?>" alt="<?= $sig['space_name']; ?>">
                <span class="bar-name small"><?= $sig['space_name']; ?></span>
                <?php if($sig['space_user_id'] == $uid['id']) { ?>
                  <sup class="red indent">+</sup>
                <?php } ?>
              </a>
            <?php } ?>
          </div> 
        </div>   
      <?php } else { ?>
        <?php if($uid['uri'] == '/') { ?>
          <div class="white-box">
            <div class="inner-padding big">
              <a href="/spaces">Подпишитесь</a> на пространства и читайте их в ленте...
            </div>
          </div>
        <?php } ?>
      <?php } ?>
    <?php } else { ?>
      <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
    <?php } ?>
    
    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="last-comm white-box sticky"> 
        <div class="inner-padding">
          <?php $num = 1; ?>
          <?php foreach ($data['latest_answers'] as  $answ)  { ?>
            <?php $num++; ?>
            <style nonce="<?= $_SERVER['nonce']; ?>">
             .comm-space-color_<?= $num; ?> {border-left: 2px solid <?= $answ['space_color']; ?>;}
            </style>
            <div class="sb-telo comm-space-color_<?= $num; ?>">
              <div class="sb-date small"> 
                <img class="ava" alt="<?= $answ['login']; ?>" src="<?= user_avatar_url($answ['avatar'], 'small'); ?>">
                <?= $answ['answer_date']; ?>
              </div> 
              <a href="/post/<?= $answ['post_id']; ?>/<?= $answ['post_slug']; ?>#answer_<?= $answ['answer_id']; ?>">
                <?= $answ['answer_content']; ?>...  
              </a>
             </div>
          <?php } ?>
        </div> 
      </div> 
    <?php } ?>
  </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>