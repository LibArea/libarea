<?php
$profile = $data['profile'];
$css = 'w110 mb-w100 ml15 z-10 -mt90 br-rd5';
if ($profile['cover_art'] != 'cover_art.jpeg') :
  $css = 'w160 mb-w100 z-10 -mt90 ml15 br-rd5';
endif;
?>

<div class="br-gray bg-white mb15">
  <?php if ($profile['cover_art'] != 'cover_art.jpeg') : ?>
    <div class="profile-box-cover relative">
      <img class="w-100 min-h100" src="<?= Html::coverUrl($profile['cover_art'], 'user'); ?>" alt="<?= $profile['login']; ?>">
    </div>
  <?php else : ?>
    <div class="profile-box w-100 relative" style="background: <?= $profile['color']; ?>;min-height: 90px;"></div>
  <?php endif; ?>
  <div class="flex justify-between">
    <div class="z-10 w-100">
      <?= Html::image($profile['avatar'], $profile['login'], $css, 'avatar', 'max'); ?>

      <?php if (UserData::checkActiveUser()) : ?>
        <div class="right m20">
          <?php if ($profile['id'] == UserData::getUserId()) : ?>
            <a class="btn btn-primary" title="<?= __('settings'); ?>" href="<?= url('setting'); ?>">
              <i class="bi-gear"></i>
            </a>
          <?php else : ?>
            <?php if ($data['button_pm'] === true) : ?>
              <a class="btn btn-primary" title="<?= __('write.message'); ?>" href="<?= url('send.messages', ['login' => $profile['login']]); ?>">
                <i class="bi-envelope"></i>
              </a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="flex">
        <h1 class="mb-block mb-text-xl m15 mb-mt5 flex flex-auto">
          <?= $profile['login']; ?>
          <?php if ($profile['name']) : ?> / <?= $profile['name']; ?><?php endif; ?>
            <?php if ($profile['up_count'] > 0) : ?>
              <i class="up-id bi-heart red mr10 ml20 mb-ml5 text-sm inline"></i>
              <sup class="text-sm gray-600 inline"><?= $profile['up_count']; ?></sup>
            <?php endif; ?>
        </h1>

        <div class="m10 mb-none right">
          <div class="flex justify-center">
            <?php if ($data['count']['count_posts'] > 0) : ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= url('profile.posts', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_posts']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= __('posts'); ?></div>
              </div>
            <?php endif; ?>

            <?php if ($data['count']['count_answers'] > 0) : ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= url('profile.answers', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_answers']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= __('answers'); ?></div>
              </div>
            <?php endif; ?>

            <?php if ($data['count']['count_comments'] > 0) : ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= url('profile.comments', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_comments']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= __('comments'); ?></div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>