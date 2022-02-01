<?php
$profile = $data['profile'];

$css = 'w110 mb-w-100 ml15 z-10 -mt80 br-rd5';
if ($profile['cover_art'] != 'cover_art.jpeg') {
  $css = 'w160 mb-w-100 ml15 z-10 -mt80 br-rd5';
}
?>

<div class="col-span-12 justify-between">
  <div class="br-box-gray bg-white">
    <?php if ($profile['cover_art'] != 'cover_art.jpeg') { ?>
      <div class="profile-box-cover relative min-h300" style="background-image: url(<?= cover_url($profile['cover_art'], 'user'); ?>); background-position: 50% 50%;"></div>
    <?php } else { ?>
      <div class="profile-box relative" style="background: <?= $profile['color']; ?>;min-height: 90px;"></div>
    <?php } ?>
    <div class="flex justify-between">
      <div class="z-10">
        <div class="z-10 mb-absolute">
          <?= user_avatar_img($profile['avatar'], 'max', $profile['login'], $css); ?>
        </div>
        <div class="flex">

          <h1 class="mb-block mb-text-xl mt20 ml15 mb0 flex">
            <?= $profile['login']; ?>
            <?php if ($profile['name']) { ?> / <?= $profile['name']; ?><?php } ?>
              <?php if ($profile['up_count'] > 0) { ?>
                <i class="up-id bi bi-heart red-500 mr10 ml20 mb-ml-5 text-sm inline"></i>
                <sup class="text-sm gray-400 inline"><?= $profile['up_count']; ?></sup>
              <?php } ?>
          </h1>

          <div class="ml120 mt10 mb-none">
            <div class="flex justify-center">
              <?php if ($data['count']['count_posts'] > 0) { ?>
                <div class="ml15 mr15 center box-number">
                  <a class="focus-user sky-500" href="<?= getUrlByName('profile.posts', ['login' => $profile['login']]); ?>">
                    <?= $data['count']['count_posts']; ?>
                  </a>
                  <div class="uppercase mt5 text-sm gray-400"><?= Translate::get('posts'); ?></div>
                </div>
              <?php } ?>

              <?php if ($data['count']['count_answers'] > 0) { ?>
                <div class="ml15 mr15 center box-number">
                  <a class="focus-user sky-500" href="<?= getUrlByName('profile.answers', ['login' => $profile['login']]); ?>">
                    <?= $data['count']['count_answers']; ?>
                  </a>
                  <div class="uppercase mt5 text-sm gray-400"><?= Translate::get('answers'); ?></div>
                </div>
              <?php } ?>

              <?php if ($data['count']['count_comments'] > 0) { ?>
                <div class="ml15 mr15 center box-number">
                  <a class="focus-user sky-500" href="<?= getUrlByName('profile.comments', ['login' => $profile['login']]); ?>">
                    <?= $data['count']['count_comments']; ?>
                  </a>
                  <div class="uppercase mt5 text-sm gray-400"><?= Translate::get('comments'); ?></div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>

      <?php if ($user['id']) { ?>
        <div class="p15 m15 right">
          <?php if ($profile['login'] == $user['login']) { ?>
            <a class="btn btn-primary" href="<?= getUrlByName('setting'); ?>">
              <i class="bi bi-gear mr5"></i>
              <?= Translate::get('settings'); ?>
            </a>
          <?php } else { ?>
            <?php if ($data['button_pm'] === true) { ?>
              <a class="btn btn-primary" href="<?= getUrlByName('send.messages', ['login' => $profile['login']]); ?>">
                <i class="bi bi-envelope mr5"></i>
                <?= Translate::get('write a message'); ?>
              </a>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>

    </div>
  </div>
</div>