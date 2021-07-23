<?php include TEMPLATE_DIR . '/header.php'; ?>

<?php if(!$uid['id']) { ?>
  <div class="banner">
    <div class="wrap-header">
      <h1 class="red"><?= Lori\Config::get(Lori\Config::PARAM_BANNER_TITLE); ?></h1>
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
            <div class="inner-padding big center gray">
                <i class="light-icon-bulb middle red"></i> 
                <span class="middle"><?= lang('space-subscription'); ?>...</span>
            </div>
        </div>
    <?php } ?>

    <?php include TEMPLATE_DIR . '/_block/post.php'; ?>
    
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
    
  </main>
  <aside>
    <?php if ($uid['id']) { ?>
      <?php if(!empty($space_user)) { ?>
        <div class="white-box">
          <div class="inner-padding"> 
            <a class="right" title="<?= lang('Spaces'); ?>" href="/spaces"><i class="light-icon-chevron-right"></i></a>
            <div class="bar-title small">
                <?= lang('Signed'); ?>
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