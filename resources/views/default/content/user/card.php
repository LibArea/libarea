<div class="br-rd5 bg-gray-100 box-shadow-all absolute ml45 user-card z-40">
  <div <?php if ($user['user_cover_art'] != 'cover_art.jpeg') { ?> style="background-image: linear-gradient(0.25turn, #fefefe, #f3f3f369), url(<?= user_cover_url($user['user_cover_art']); ?>); background-position: 50% 50%;" <?php } ?> class="p10">

    <div class="flex ">
      <div class="-mt50">
        <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'w94 br-rd-50 mr15'); ?>
      </div>
      <div class="w-100 w400">
        <?php if ($uid['user_id'] > 0 && $uid['user_login'] != $user['user_login']) { ?>
          <a class="right bg-blue-800 br-box-blue bg-hover-light-blue pt5 pr15 pb5 pl15 block br-rd5 white white-hover center size-14" href="<?= getUrlByName('user.send.messages', ['login' => $user['user_login']]); ?>">
            <i class="bi bi-envelope middle mr5"></i>
            <?= Translate::get('message'); ?>
          </a>
        <?php } ?>
        <a class="block size-24 black" href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>">
          <?= $user['user_login']; ?>
        </a>
        <?php if ($user['user_name']) { ?>
          <div class="size-15">
            <?= $user['user_name']; ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="gray size-15 mt5">
      <?= $user['user_about']; ?>
    </div>
    <?php if (!empty($post['post_id'])) { ?>
      <div class="gray-light size-15 mt5">
        <?= Translate::get('post'); ?>:
        <a class="black dark-white" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['post_title']; ?>
        </a>
      </div>
    <?php } ?>
    <?php if ($user['user_website']) { ?>
      <div class="gray-light size-15 mt5">
        <?= Translate::get('website'); ?>:
        <a class="black dark-white" rel="noopener nofollow ugc" href="<?= $user['user_website']; ?>">
          <?= $user['user_website']; ?>
        </a>
      </div>
    <?php } ?>
    <div class="br-bottom mt10 mb5"></div>
    <div class="mt50  lowercase size-14">
      <?php if ($user['user_up_count'] > 0) { ?>
        <i class="up-id bi bi-heart middle red mr5"></i>
        <span class="size-14  mr15"><?= $user['user_up_count']; ?></span>
      <?php } ?>
      <?php if ($user['user_hits_count'] > 0) { ?>
        <i class="bi bi-eye middle mr5 ml5"></i>
        <span class="size-14 mr15"> <?= $user['user_hits_count']; ?></span>
      <?php } ?>
      <i title="<?= Translate::get('medal for registration'); ?>" class="bi bi-gift right mr5 ml5 blue"></i>
      <?php if ($user['user_id'] < 50) { ?>
        <i title="<?= Translate::get('joined in the early days'); ?>" class="bi bi-award right mr5 ml5 green"></i>
      <?php } ?>
      <?php foreach ($badges as $badge) { ?>
        <?= $badge['badge_icon']; ?>
      <?php } ?>
    </div>

  </div>
</div>