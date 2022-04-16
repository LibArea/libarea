<div class="br-rd5 bg-lightgray w-90 box-shadow-all absolute ml45 z-40">
  <div <?php if ($user['cover_art'] != 'cover_art.jpeg') { ?> style="background-image: linear-gradient(0.25turn, #fefefe, #f3f3f369), url(<?= Html::coverUrl($user['cover_art'], 'user'); ?>); background-position: 50% 50%;" <?php } ?> class="p10">

    <div class="flex">
      <div class="-mt50">
        <?= Html::image($user['avatar'], $user['login'], 'w94 br-rd-50 mr15', 'avatar', 'max'); ?>
      </div>
      <div class="w-100">
        <?php if (UserData::checkActiveUser() && $user['login'] != $user['login']) : ?>
          <a class="right btn btn-primary block" href="<?= getUrlByName('send.messages', ['login' => $user['login']]); ?>">
            <i class="bi-envelope middle mr5"></i>
            <?= __('message'); ?>
          </a>
        <?php endif; ?>
        <a class="block text-2xl" href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
          <?= $user['login']; ?>
        </a>
        <?php if ($user['name']) : ?>
          <div>
            <?= $user['name']; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="gray mt5 text-sm">
      <?= $user['about']; ?>
    </div>
    <?php if (!empty($post['post_id'])) : ?>
      <div class="gray-600 mt5">
        <?= __('post'); ?>:
        <a href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['post_title']; ?>
        </a>
      </div>
    <?php endif; ?>
    <?php if ($user['website']) : ?>
      <div class="gray-600 mt5">
        <?= __('website'); ?>:
        <a rel="noopener nofollow ugc" href="<?= $user['website']; ?>">
          <?= $user['website']; ?>
        </a>
      </div>
    <?php endif; ?>
    <div class="br-bottom mt10 mb5"></div>
    <div class="mt50  lowercase text-sm">
      <?php if ($user['up_count'] > 0) : ?>
        <i class="up-id bi-heart middle red mr5"></i>
        <span class="text-sm mr15"><?= $user['up_count']; ?></span>
      <?php endif; ?>
      <?php if ($user['hits_count'] > 0) : ?>
        <i class="bi-eye middle mr5 ml5"></i>
        <span class="text-sm mr15"> <?= $user['hits_count']; ?></span>
      <?php endif; ?>
      <i title="<?= __('medal.registration'); ?>" class="bi-gift right mr5 ml5 sky"></i>
      <?php if ($user['id'] < 50) : ?>
        <i title="<?= __('first.days'); ?>" class="bi-award right mr5 ml5 green"></i>
      <?php endif; ?>
      <?php foreach ($badges as $badge) : ?>
        <span class="ml15"><?= $badge['badge_icon']; ?></span>
      <?php endforeach; ?>
    </div>

  </div>
</div>