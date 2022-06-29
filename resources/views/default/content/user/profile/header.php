<?php
$profile = $data['profile'];
$css = 'w94 mb-w100 ml15 -mt50 br-rd5';
if ($profile['cover_art'] != 'cover_art.jpeg') :
  $css = 'w160 mb-w100 -mt90 ml15 br-rd5';
endif;
?>
<style nonce="<?= $_SERVER['nonce']; ?>">.bg-profile {background: <?= $profile['color']; ?>;min-height: 90px;}</style>
<div class="br-gray bg-white mb15">
  <?php if ($profile['cover_art'] != 'cover_art.jpeg') : ?>
    <div class="profile-box-cover relative">
      <img class="w-100" src="<?= Html::coverUrl($profile['cover_art'], 'user'); ?>" alt="<?= $profile['login']; ?>">
    </div>
  <?php else : ?>
    <div class="w-100 relative bg-profile"></div>
  <?php endif; ?>
  <div class="flex justify-between">
    <div class="profile-header-footer">
      <?= Html::image($profile['avatar'], $profile['login'], $css, 'avatar', 'max'); ?>

      <?php if (UserData::checkActiveUser()) : ?>
        <div class="right m15">
          <?php if ($profile['id'] == UserData::getUserId()) : ?>
            <a class="btn btn-primary" title="<?= __('app.settings'); ?>" href="<?= url('setting'); ?>">
              <svg class="icons"><use xlink:href="/assets/svg/icons.svg#settings"></use></svg>
            </a>
          <?php else : ?>
            <?php if ($data['button_pm'] === true) : ?>
              <a class="btn btn-primary" title="<?= __('app.write_message'); ?>" href="<?= url('send.messages', ['login' => $profile['login']]); ?>">
                <svg class="icons"><use xlink:href="/assets/svg/icons.svg#mail"></use></svg>
              </a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="flex">
        <h1 class="mb-block mb-text-xl m15 flex flex-auto">
          <?= $profile['login']; ?>
          <?php if ($profile['name']) : ?> / <?= $profile['name']; ?><?php endif; ?>
            <?php if ($profile['up_count'] > 0) : ?>
              <svg class="icons red mr5 ml20"><use xlink:href="/assets/svg/icons.svg#heart"></use></svg>
              <sup class="text-sm gray-600 inline"><?= Html::formatToHuman($profile['up_count']); ?></sup>
            <?php endif; ?>
        </h1>

        <div class="m10 mb-none right">
          <div class="flex justify-center">
            <?php if ($data['count']['count_posts'] > 0) : ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= url('profile.posts', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_posts']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= __('app.posts'); ?></div>
              </div>
            <?php endif; ?>

            <?php if ($data['count']['count_answers'] > 0) : ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= url('profile.answers', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_answers']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= __('app.answers'); ?></div>
              </div>
            <?php endif; ?>

            <?php if ($data['count']['count_comments'] > 0) : ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= url('profile.comments', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_comments']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= __('app.comments'); ?></div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>