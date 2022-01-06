<?php
$user = $data['user'];

$css = 'w110 h110 mb-w100 mb-h100 ml15 z-10 -mt80 br-rd5';
if ($user['user_cover_art'] != 'cover_art.jpeg') {
  $css = 'w160 h160 mb-w100 mb-h100 ml15 z-10 -mt80 br-rd5';
}
?>

<div class="col-span-12 justify-between">
  <div class="br-box-gray bg-white">
    <?php if ($user['user_cover_art'] != 'cover_art.jpeg') { ?>
      <div class="profile-box-cover relative min-h300 mb-min-h100" style="background-image: url(<?= cover_url($user['user_cover_art'], 'user'); ?>); background-position: 50% 50%;"></div>
    <?php } else { ?>
      <div class="profile-box relative" style="background: <?= $user['user_color']; ?>;min-height: 90px;"></div>
    <?php } ?>
    <div class="flex justify-between">
      <div class="z-10">
        <div class="z-10 mb-absolute">
          <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], $css); ?>
        </div>
        <div class="flex">

          <h1 class="text-2xl5 font-normal mb-block m15 mb5 flex justify-between">
            <?= $user['user_login']; ?>
            <?php if ($user['user_name']) { ?> / <?= $user['user_name']; ?><?php } ?>
              <?php if ($user['user_up_count'] > 0) { ?>
                <div class="flex">
                  <div class="up-id bi bi-heart red-500 mr10 ml20 mb-ml-5 text-sm"></div>
                  <div class="text-sm gray-600"><?= $user['user_up_count']; ?></div>
                </div>
              <?php } ?>
          </h1>

          <div class="ml120 mt10 mb-none">

            <div class="flex justify-center">

              <?php if ($data['count']['count_posts'] > 0) { ?>
                <div class="ml15 mr15 center box-number">
                  <a class="focus-user sky-500" href="<?= getUrlByName('profile.posts', ['login' => $user['user_login']]); ?>">
                    <?= $data['count']['count_posts']; ?>
                  </a>
                  <div class="uppercase mt5 text-sm gray"><?= Translate::get('posts'); ?></div>
                </div>
              <?php } ?>

              <?php if ($data['count']['count_answers'] > 0) { ?>
                <div class="ml15 mr15 center box-number">
                  <a class="focus-user sky-500" href="<?= getUrlByName('profile.answers', ['login' => $user['user_login']]); ?>">
                    <?= $data['count']['count_answers']; ?>
                  </a>
                  <div class="uppercase mt5 text-sm gray"><?= Translate::get('answers'); ?></div>
                </div>
              <?php } ?>

              <?php if ($data['count']['count_comments'] > 0) { ?>
                <div class="ml15 mr15 center box-number">
                  <a class="focus-user sky-500" href="<?= getUrlByName('profile.comments', ['login' => $user['user_login']]); ?>">
                    <?= $data['count']['count_comments']; ?>
                  </a>
                  <div class="uppercase mt5 text-sm gray"><?= Translate::get('comments'); ?></div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>


      <?php if ($uid['user_id'] > 0) { ?>
        <div class="p15 m15 right">
          <?php if ($uid['user_login'] == $user['user_login']) { ?>
            <a class="btn btn-primary" href="<?= getUrlByName('setting'); ?>">
              <i class="bi bi-gear mr5"></i>
              <?= Translate::get('settings'); ?>
            </a>
          <?php } else { ?>
            <?php if ($data['button_pm'] === true) { ?>
              <a class="btn btn-primary" href="<?= getUrlByName('send.messages', ['login' => $user['user_login']]); ?>">
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