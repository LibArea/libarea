<!DOCTYPE html>
<html lang="ru" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <?= returnBlock('/meta-tags', ['meta' => $meta]); ?>
  <?php getRequestHead()->output(); ?>
  <script src="/assets/js/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="/assets/css/style.css">
  <?php if ($uid['user_id'] == 0) { ?>
    <script nonce="<?= $_SERVER['nonce']; ?>">
      $(document).on('click', '.click-no-auth', function() {
        layer.msg('<?= lang('You need to log in'); ?>');
      });
    </script>
  <?php } ?>
</head>

<?php if ($uid['user_id'] == 0) { ?>
  <?php if ($meta['sheet'] == 'feed' || $meta['sheet'] == 'top') { ?>
    <link rel="stylesheet" href="/assets/css/banner.css">

    <body class="black pt0<?php if (Request::getCookie('dayNight') == 'dark') { ?> dark<?php } ?>">
      <link rel="stylesheet" href="/assets/css/banner.css">
    <?php } ?>
    <header class="flex justify-content-between align-items-center gray-light-2 banner banner-bg">
    <?php } else { ?>

      <body class="black<?php if (Request::getCookie('dayNight') == 'dark') { ?> dark<?php } ?>">
        <header class="flex justify-content-between align-items-center gray-light-2">
        <?php } ?>

        <div class="flex align-items-center">
          <a title="<?= lang('Home'); ?>" class="logo" href="/">
            AGOUTI
          </a>
          <div>
            <form class="form" method="post" action="<?= getUrlByName('search'); ?>">
              <?= csrf_field() ?>
              <input type="text" name="q" id="search" placeholder="<?= lang('To find'); ?>..." class="form-search">
            </form>
          </div>
          <div>
            <a class="gray-light-2 ml30 no-mob" title="<?= lang('Spaces'); ?>" href="<?= getUrlByName('spaces'); ?>">
              <i class="icon-infinity size-21"></i>
              <span><?= lang('Spaces'); ?></span>
            </a>
            <a class="gray-light-2 ml30 no-mob" title="<?= lang('Topics'); ?>" href="<?= getUrlByName('topics'); ?>">
              <i class="icon-clone size-21"></i>
              <span><?= lang('Topics'); ?></span>
            </a>
          </div>
        </div>
        <div class="flex align-items-center">
          <div class="ml30">
            <span id="toggledark" class="my-color-m">
              <i class="icon-sun size-21"></i>
            </span>
          </div>
          <?php if (!$uid['user_id']) { ?>
            <?php if (!Agouti\Config::get(Agouti\Config::PARAM_INVITE)) { ?>
              <div class="ml30 register">
                <a class="green size-15" title="<?= lang('Sign up'); ?>" href="<?= getUrlByName('register'); ?>">
                  <?= lang('Sign up'); ?>
                </a>
              </div>
            <?php } ?>
            <div class="ml30 login mr5">
              <a class="gray size-15" title="<?= lang('Sign in'); ?>" href="<?= getUrlByName('login'); ?>">
                <?= lang('Sign in'); ?>
              </a>
            </div>
          <?php } else { ?>
            <?php if ($uid['notif']) { ?>
              <div class="ml30 notif">
                <a href="<?= getUrlByName('notifications', ['login' => $uid['user_login']]); ?>">
                  <?php if ($uid['notif']['notification_action_type'] == 1) { ?>
                    <i class="icon-mail size-21 red"></i>
                  <?php } else { ?>
                    <i class="icon-bell size-21 red"></i>
                  <?php } ?>
                </a>
              </div>
            <?php } ?>
            <div class="ml30 add-post">
              <a title="<?= lang('Add post'); ?>" href="/post/add">
                <i class="icon-plus blue size-21 middle"></i>
              </a>
            </div>
            <div class="dropbtn ml30">
              <div class="nick gray" title="<?= $uid['user_login']; ?>">
                <?= $uid['user_login']; ?>
                <?= user_avatar_img($uid['user_avatar'], 'small', $uid['user_login'], 'ava-24 ml5'); ?>
                <i class="icon-down-dir middle"></i>
              </div>
              <div class="dropdown-menu absolute">
                <span class="st"></span>
                <a class="dr-menu" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>">
                  <i class="icon-user-o middle"></i>
                  <span class="middle size-13"><?= lang('Profile'); ?></span>
                </a>
                <a class="dr-menu" href="<?= getUrlByName('setting', ['login' => $uid['user_login']]); ?>">
                  <i class="icon-cog-outline middle"></i>
                  <span class="middle size-13"><?= lang('Settings'); ?></span>
                </a>
                <a class="dr-menu" href="<?= getUrlByName('drafts', ['login' => $uid['user_login']]); ?>">
                  <i class="icon-edit middle"></i>
                  <span class="middle size-13"><?= lang('Drafts'); ?></span>
                </a>
                <a class="dr-menu" href="<?= getUrlByName('notifications', ['login' => $uid['user_login']]); ?>">
                  <i class="icon-lightbulb middle"></i>
                  <span class="middle size-13"><?= lang('Notifications'); ?></span>
                </a>
                <a class="dr-menu" href="<?= getUrlByName('messages', ['login' => $uid['user_login']]); ?>">
                  <i class="icon-mail middle"></i>
                  <span class="middle size-13"><?= lang('Messages-m'); ?></span>
                </a>
                <a class="dr-menu" href="<?= getUrlByName('favorites', ['login' => $uid['user_login']]); ?>">
                  <i class="icon-bookmark-empty middle"></i>
                  <span class="middle size-13"><?= lang('Favorites'); ?></span>
                </a>
                <?php if ($uid['user_trust_level'] > 1) { ?>
                  <a class="dr-menu" href="<?= getUrlByName('invitations', ['login' => $uid['user_login']]); ?>">
                    <i class="icon-user-add-outline middle"></i>
                    <span class="middle size-13"><?= lang('Invites'); ?></span>
                  </a>
                <?php } ?>
                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a class="dr-menu" href="<?= getUrlByName('admin'); ?>" target="_black">
                    <i class="icon-tools middle"></i>
                    <span class="middle size-13"><?= lang('Admin'); ?></span>
                  </a>
                <?php } ?>
                <hr>
                <a class="dr-menu" href="<?= getUrlByName('logout'); ?>" class="logout" title="<?= lang('Sign out'); ?>">
                  <i class="icon-logout middle"></i>
                  <span class="middle size-13"><?= lang('Sign out'); ?></span>
                </a>
              </div>
            </div>
          <?php } ?>
        </div>
        </header>