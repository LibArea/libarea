<?php
$profile = $data['profile'];
$css = 'w110 mb-w100 ml15 z-10 -mt90 br-rd5';
if ($profile['cover_art'] != 'cover_art.jpeg') {
  $css = 'w160 mb-w100 z-10 -mt90 ml15 br-rd5';
}
?>

<div class="br-box-gray bg-white mb15">
  <?php if ($profile['cover_art'] != 'cover_art.jpeg') { ?>
    <div class="profile-box-cover relative">
      <img class="w-100 min-h100" src="<?= cover_url($profile['cover_art'], 'user'); ?>" alt="<?= $profile['login']; ?>">
    </div>
  <?php } else { ?>
    <div class="profile-box w-100 relative" style="background: <?= $profile['color']; ?>;min-height: 90px;"></div>
  <?php } ?>
  <div class="flex justify-between">
    <div class="z-10 w-100">
      <?= user_avatar_img($profile['avatar'], 'max', $profile['login'], $css); ?>

      <?php if ($user['id']) { ?>
        <div class="right m15">
          <?php if ($profile['login'] == $user['login']) { ?>
            <a class="btn btn-primary" href="<?= getUrlByName('setting'); ?>">
              <i class="bi-gear mr5"></i>
              <?= Translate::get('settings'); ?>
            </a>
          <?php } else { ?>
            <?php if ($data['button_pm'] === true) { ?>
              <a class="btn btn-primary" href="<?= getUrlByName('send.messages', ['login' => $profile['login']]); ?>">
                <i class="bi-envelope mr5"></i>
                <?= Translate::get('write a message'); ?>
              </a>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>

      <div class="flex">
        <h1 class="mb-block mb-text-xl m15 mb-mt5 flex">
          <?= $profile['login']; ?>
          <?php if ($profile['name']) { ?> / <?= $profile['name']; ?><?php } ?>
            <?php if ($profile['up_count'] > 0) { ?>
              <i class="up-id bi-heart red mr10 ml20 mb-ml5 text-sm inline"></i>
              <sup class="text-sm gray-600 inline"><?= $profile['up_count']; ?></sup>
            <?php } ?>
        </h1>

        <div class="ml120 mt10 mb-none">
          <div class="flex justify-center">
            <?php if ($data['count']['count_posts'] > 0) { ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= getUrlByName('profile.posts', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_posts']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= Translate::get('posts'); ?></div>
              </div>
            <?php } ?>

            <?php if ($data['count']['count_answers'] > 0) { ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= getUrlByName('profile.answers', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_answers']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= Translate::get('answers'); ?></div>
              </div>
            <?php } ?>

            <?php if ($data['count']['count_comments'] > 0) { ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= getUrlByName('profile.comments', ['login' => $profile['login']]); ?>">
                  <?= $data['count']['count_comments']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= Translate::get('comments'); ?></div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>