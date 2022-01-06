<div class="br-rd5 bg-gray-100 w400 box-shadow-all absolute ml45 user-card z-40">
  <div <?php if ($user['user_cover_art'] != 'cover_art.jpeg') { ?> style="background-image: linear-gradient(0.25turn, #fefefe, #f3f3f369), url(<?= cover_url($user['user_cover_art'], 'user'); ?>); background-position: 50% 50%;" <?php } ?> class="p10">

    <div class="flex">
      <div class="-mt50">
        <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'w94 br-rd-50 mr15'); ?>
      </div>
      <div class="w-100">
        <?php if ($uid['user_id'] > 0 && $uid['user_login'] != $user['user_login']) { ?>
          <a class="right btn btn-primary block" href="<?= getUrlByName('send.messages', ['login' => $user['user_login']]); ?>">
            <i class="bi bi-envelope middle mr5"></i>
            <?= Translate::get('message'); ?>
          </a>
        <?php } ?>
        <a class="block text-2xl" href="<?= getUrlByName('profile', ['login' => $user['user_login']]); ?>">
          <?= $user['user_login']; ?>
        </a>
        <?php if ($user['user_name']) { ?>
          <div>
            <?= $user['user_name']; ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="gray mt5">
      <?= $user['user_about']; ?>
    </div>
    <?php if (!empty($post['post_id'])) { ?>
      <div class="gray-600 mt5">
        <?= Translate::get('post'); ?>:
        <a href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['post_title']; ?>
        </a>
      </div>
    <?php } ?>
    <?php if ($user['user_website']) { ?>
      <div class="gray-600 mt5">
        <?= Translate::get('website'); ?>:
        <a rel="noopener nofollow ugc" href="<?= $user['user_website']; ?>">
          <?= $user['user_website']; ?>
        </a>
      </div>
    <?php } ?>
    <div class="br-bottom mt10 mb5"></div>
    <div class="mt50  lowercase text-sm">
      <?php if ($user['user_up_count'] > 0) { ?>
        <i class="up-id bi bi-heart middle red mr5"></i>
        <span class="text-sm mr15"><?= $user['user_up_count']; ?></span>
      <?php } ?>
      <?php if ($user['user_hits_count'] > 0) { ?>
        <i class="bi bi-eye middle mr5 ml5"></i>
        <span class="text-sm mr15"> <?= $user['user_hits_count']; ?></span>
      <?php } ?>
      <i title="<?= Translate::get('medal for registration'); ?>" class="bi bi-gift right mr5 ml5 sky-500"></i>
      <?php if ($user['user_id'] < 50) { ?>
        <i title="<?= Translate::get('joined in the early days'); ?>" class="bi bi-award right mr5 ml5 green-600"></i>
      <?php } ?>
      <?php foreach ($badges as $badge) { ?>
        <span class="ml15"><?= $badge['badge_icon']; ?></span>
      <?php } ?>
    </div>

  </div>
</div>