<!DOCTYPE html>
<html lang="ru" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <?= includeTemplate('/_block/meta-tags', ['meta' => $meta]); ?>
  <?php getRequestHead()->output(); ?>
  <script src="/assets/js/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="/assets/css/style.css">
  <?php if ($uid['user_id'] == 0) { ?>
    <script nonce="<?= $_SERVER['nonce']; ?>">
      $(document).on('click', '.click-no-auth', function() {
        layer.msg('<?= lang('you need to log in'); ?>');
      });
    </script>
  <?php } ?>
</head>

<body class="black bg-gray-100<?php if (Request::getCookie('dayNight') == 'dark') { ?> dark<?php } ?>">

  <header class="bg-white border-bottom mt0 mb15 sticky top0 z-30">
    <div class="col-span-12 wrap pr10 pl10 grid items-center grid-cols-12">
      <div class="col-span-8 flex items-center">
        <div class="lateral no-pc mr10 flex items-center">
          <i class="bi bi-list gray-light-2 size-18"></i>
          <div class="lateral-menu bg-white br-rd-3 p5 bg-white absolute">
            <nav class="justify-between mt0 ml0 pl0 t-81 sticky size-15 mt5">
              <a class="pt5 pr10 pb5 pl10 gray block bg-hover-100" href="<?= getUrlByName('spaces'); ?>">
                <i class="bi bi-command middle"></i>
                <span class="ml5"><?= lang('spaces'); ?></span>
              </a>
              <a class="pt5 pr10 pb5 pl10 gray block bg-hover-100" href="<?= getUrlByName('topics'); ?>">
                <i class="bi bi-columns-gap middle"></i>
                <span class="ml5"><?= lang('topics'); ?></span>
              </a>
              <a class="pt5 pr10 pb5 pl10 gray block bg-hover-100" href="<?= getUrlByName('users'); ?>">
                <i class="bi bi-people middle"></i>
                <span class="ml5"><?= lang('users'); ?></span>
              </a>
              <a class="pt5 pr10 pb5 pl10 gray block bg-hover-100" href="<?= getUrlByName('domains'); ?>">
                <i class="bi bi-link-45deg middle"></i>
                <span class="ml5"><?= lang('domains'); ?></span>
              </a>
            </nav>
          </div>
        </div>
        <div class="mr20 flex items-center">
          <a title="<?= lang('home'); ?>" class="size-21 mb-size-18 p5 black" href="/">
            AGOUTI
          </a>
        </div>
        <div class="p10 ml20 no-mob w-100">
          <form class="form r" method="post" action="<?= getUrlByName('search'); ?>">
            <?= csrf_field() ?>
            <input type="text" name="q" id="search" placeholder="<?= lang('to find'); ?>..." class="h40 bg-gray-100 p5 br-rd-5 size-18 gray w-100">
          </form>
        </div>
      </div>
      <?php if ($uid['user_id'] == 0) { ?>
        <div class="col-span-4">
          <div class="flex right items-center">
            <div class="header-menu-item no-mob only-icon p10 ml20 mb-ml-10">
              <span id="toggledark" class="my-color-m">
                <i class="bi bi-brightness-high gray-light-2 size-18"></i>
              </span>
            </div>
            <?php if (!Agouti\Config::get(Agouti\Config::PARAM_INVITE)) { ?>
              <div class="ml30 register">
                <a class="gray size-15" title="<?= lang('sign up'); ?>" href="<?= getUrlByName('register'); ?>">
                  <?= lang('sign up'); ?>
                </a>
              </div>
            <?php } ?>
            <div class="ml30 mr5">
              <a class="button-primary size-15 br-rd-5 pt5 pr10 pb5 pl10" title="<?= lang('sign in'); ?>" href="<?= getUrlByName('login'); ?>">
                <?= lang('sign in'); ?>
              </a>
            </div>
          </div>
        </div>
      <?php } else { ?>
        <div class="col-span-4">
          <div class="flex right items-center">
            <div class="center p10 no-pc">
              <a href="/post/add" class="blue">
                <i class="bi bi-plus-lg size-18"></i>
              </a>
            </div>
            <div class="header-menu-item only-icon p10 ml20 mb-ml-10">
              <span id="toggledark" class="my-color-m">
                <i class="bi bi-brightness-high gray-light-2 size-18"></i>
              </span>
            </div>
            <?php if ($uid['user_id'] > 0) { ?>
              <div class="p10 ml20 mb-ml-10">
                <a class="gray-light-2" href="<?= getUrlByName('notifications', ['login' => $uid['user_login']]); ?>">
                  <?php if ($uid['notif']) { ?>
                    <?php if ($uid['notif']['notification_action_type'] == 1) { ?>
                      <i class="bi bi-envelope size-18 red"></i>
                    <?php } else { ?>
                      <i class="bi bi-bell-fill size-18 red"></i>
                    <?php } ?>
                  <?php } else { ?>
                    <i class="bi bi-bell mb-size-18 size-18"></i>
                  <?php } ?>
                </a>
              </div>
            <?php } ?>
            <div class="dropbtn relative p10 ml20 mb-ml-10">
              <a class="relative w-auto">
                <?= user_avatar_img($uid['user_avatar'], 'small', $uid['user_login'], 'w34 br-rd-50'); ?>
              </a>
              <div class="dropdown-menu right0 bg-white br-rd-3 p5 absolute">
                <div class="bg-white size-15">
                  <span class="st"></span>
                  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>">
                    <i class="bi bi-person middle mr5"></i>
                    <span class="middle size-14"><?= lang('profile'); ?></span>
                  </a>
                  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('setting', ['login' => $uid['user_login']]); ?>">
                    <i class="bi bi-gear middle mr5"></i>
                    <span class="middle size-14"><?= lang('settings'); ?></span>
                  </a>
                  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('drafts', ['login' => $uid['user_login']]); ?>">
                    <i class="bi bi-pencil-square middle mr5"></i>
                    <span class="middle size-14"><?= lang('drafts'); ?></span>
                  </a>
                  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('notifications', ['login' => $uid['user_login']]); ?>">
                    <i class="bi bi-app-indicator middle mr5"></i>
                    <span class="middle size-14"><?= lang('notifications'); ?></span>
                  </a>
                  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('messages', ['login' => $uid['user_login']]); ?>">
                    <i class="bi bi-envelope middle mr5"></i>
                    <span class="middle size-14"><?= lang('messages-m'); ?></span>
                  </a>
                  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('favorites', ['login' => $uid['user_login']]); ?>">
                    <i class="bi bi-bookmark middle mr5"></i>
                    <span class="middle size-14"><?= lang('favorites'); ?></span>
                  </a>
                  <?php if ($uid['user_trust_level'] > 1) { ?>
                    <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('invitations', ['login' => $uid['user_login']]); ?>">
                      <i class="bi bi-person-plus middle mr5"></i>
                      <span class="middle size-14"><?= lang('invites'); ?></span>
                    </a>
                  <?php } ?>
                  <?php if ($uid['user_trust_level'] == 5) { ?>
                    <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('admin'); ?>" target="_black">
                      <i class="bi bi-shield-exclamation middle mr5"></i>
                      <span class="middle size-14"><?= lang('admin'); ?></span>
                    </a>
                  <?php } ?>
                  <hr>
                  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" href="<?= getUrlByName('logout'); ?>" class="logout" title="<?= lang('sign out'); ?>">
                    <i class="bi bi-box-arrow-right middle mr5"></i>
                    <span class="middle size-14"><?= lang('sign out'); ?></span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php }  ?>
    </div>
  </header>
  <div class="wrap grid grid-cols-12 gap-4 pr10 pl10 justify-between">