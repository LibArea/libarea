<?php
$profile = $data['profile'];
$css = 'img-xl mt15 mb-w100 profile-ava';
if ($profile['cover_art'] != 'cover_art.jpeg') :
  $css = 'img-2xl mt60 mb-mt5 mb-img-2xl profile-ava';
endif;
?>
<style nonce="<?= $_SERVER['nonce']; ?>">.bg-profile {background: <?= $profile['color']; ?>;min-height: 90px;}</style>

<div class="br-gray bg-white mb15 relative">
  <?php if (UserData::checkAdmin()) : ?>
    <?= insert('/_block/number-removed-content', ['count' => $data['delet_count']]); ?>
  <?php endif; ?>

  <?= Img::avatar($profile['avatar'], $profile['login'], $css, 'max'); ?>

  <?php if ($profile['cover_art'] != 'cover_art.jpeg') : ?>
    <div class="relative hidden">
      <img class="box-cover-img" src="<?= Img::cover($profile['cover_art'], 'user'); ?>" alt="<?= $profile['login']; ?>">
    </div>
  <?php else : ?>
    <div class="box-cover-img relative bg-profile"></div>
  <?php endif; ?>
    <div class="profile-header-footer mt15">
       
      <?php if (UserData::checkActiveUser()) : ?>
        <div class="right m15">
          <?php if ($profile['id'] == UserData::getUserId()) : ?>
            <a class="btn btn-primary" title="<?= __('app.settings'); ?>" href="<?= url('setting'); ?>">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#settings"></use>
              </svg>
            </a>
          <?php else : ?>
            <?php if ($data['button_pm'] === true) : ?>
              <a class="btn btn-primary" title="<?= __('app.write_message'); ?>" href="<?= url('send.messages', ['login' => $profile['login']]); ?>">
                <svg class="icons">
                  <use xlink:href="/assets/svg/icons.svg#mail"></use>
                </svg>
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
              <svg class="icons red mr5 ml20">
                <use xlink:href="/assets/svg/icons.svg#heart"></use>
              </svg>
              <sup class="text-sm gray-600 inline"><?= Html::formatToHuman($profile['up_count']); ?></sup>
            <?php endif; ?>
        </h1>

        <div class="m10 mb-none right">
          <div class="flex justify-center">
            <?php if ($data['counts']['count_posts'] > 0) : ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= url('profile.posts', ['login' => $profile['login']]); ?>">
                  <?= $data['counts']['count_posts']; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= __('app.posts'); ?></div>
              </div>
            <?php endif; ?>

        
            <?php $comentsCount = $data['counts']['count_comments'] + $data['counts']['count_answers']; ?>
            <?php if ($comentsCount > 0) : ?>
              <div class="ml15 mr15 center box-number">
                <a class="focus-user sky" href="<?= url('profile.comments', ['login' => $profile['login']]); ?>">
                  <?= $comentsCount; ?>
                </a>
                <div class="uppercase mt5 text-sm gray-600"><?= __('app.comments'); ?></div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
</div>