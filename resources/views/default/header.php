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

  <header class="bg-white border-bottom mt0 mb15 sticky z-30">
    <div class="col-span-12 wrap pr10 pl10 grid items-center grid-cols-12">
      <div class="col-span-7 flex items-center">
        <div class="lateral no-pc mr10 flex items-center">
          <i class="icon-menu size-18"></i>
          <div class="lateral-menu bg-white br-rd-3 p5 absolute">
            <div class="bg-white">
                <nav class="justify-between mt0 ml0 pl0 t-81 sticky">
                  <div class="size-15 mt5">
                    <a class="block mb10" href="<?= getUrlByName('spaces'); ?>">
                      <i class="icon-infinity gray-light-2 size-18"></i>
                      <span class="black"><?= lang('spaces'); ?></span>
                    </a>
                    <a class="block mb10" href="<?= getUrlByName('topics'); ?>">
                      <i class="icon-clone gray-light-2 size-18"></i>
                      <span class="black"><?= lang('topics'); ?></span>
                    </a>
                    <a class="block mb10" href="<?= getUrlByName('users'); ?>">
                      <i class="icon-user-o gray-light-2 size-18"></i>
                      <span class="black"><?= lang('users'); ?></span>
                    </a>
                    <div class="header-menu-item mb5 only-icon">
                      <span id="toggledark" class="my-color-m">
                      <i class="icon-sun gray-light-2 size-18"></i>
                      <span class="black"><?= lang('color'); ?></span>
                      </span>
                    </div>
                  </div>
                </nav>
            </div>
          </div>
        </div>
        <div class="mr20 flex items-center">
          <a title="<?= lang('home'); ?>" class="logo size-21 p5 black" href="/">
            AGOUTI
          </a>
        </div>
        <div class="p10 ml20 no-mob w-100">
          <form class="form r" method="post" action="<?= getUrlByName('search'); ?>">
            <?= csrf_field() ?>
            <input type="text" name="q" id="search" placeholder="<?= lang('to find'); ?>..." class="form-search br-rd-5 size-18 gray w-100">
          </form>
        </div>
      </div>
      <?php if ($uid['user_id'] == 0) { ?>
        <div class="col-span-5">
          <div class="flex right items-center">
            <div class="header-menu-item no-mob only-icon p10 ml20 mb-ml-10">
              <span id="toggledark" class="my-color-m">
                <i class="icon-sun gray-light-2 size-26"></i>
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
              <a class="button-primary br-rd-5 pt5 pr10 pb5 pl10" title="<?= lang('sign in'); ?>" href="<?= getUrlByName('login'); ?>">
                <?= lang('sign in'); ?>
              </a>
            </div>
          </div>
        </div>
      <?php } else { ?>
        <div class="col-span-5">
          <div class="flex right nitems-center">
            <div class="center p10 no-pc">
              <a href="/post/add" class="blue">
                <i class="icon-pencil size-26"></i>
              </a>
            </div>
            <div class="header-menu-item only-icon no-mob p10 ml20 mb-ml-10">
              <span id="toggledark" class="my-color-m">
                <i class="icon-sun gray-light-2 size-26"></i>
              </span>
            </div>
            <?php if ($uid['user_id'] > 0) { ?>
              <div class="p10 ml20 mb-ml-10">
                <a href="<?= getUrlByName('notifications', ['login' => $uid['user_login']]); ?>">
                  <?php if ($uid['notif']) { ?>
                    <?php if ($uid['notif']['notification_action_type'] == 1) { ?>
                      <i class="icon-mail gray-light-2 size-26 red"></i>
                    <?php } else { ?>
                      <i class="icon-bell-alt gray-light-2 size-26<?= $uid['notif'] ? ' red' : ''; ?>"></i>
                    <?php } ?>
                  <?php } else { ?>
                    <i class="icon-bell gray-light-2 size-26"></i>
                  <?php } ?>
                </a>
              </div>
            <?php } ?>

            <div class="dropbtn relative p10 ml20 mb-ml-10">
              <a class="relative w-auto">
                <?= user_avatar_img($uid['user_avatar'], 'small', $uid['user_login'], 'w44 br-rd-50'); ?>
              </a>
              <div class="dropdown-menu bg-white br-rd-3 p5 absolute">
                <div class="bg-white size-15">
                  <span class="st"></span>
                  <a class="dr-menu block" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>">
                    <i class="icon-user-o middle"></i>
                    <span class="middle"><?= lang('profile'); ?></span>
                  </a>
                  <a class="dr-menu block" href="<?= getUrlByName('setting', ['login' => $uid['user_login']]); ?>">
                    <i class="icon-cog-outline middle"></i>
                    <span class="middle"><?= lang('settings'); ?></span>
                  </a>
                  <a class="dr-menu block" href="<?= getUrlByName('drafts', ['login' => $uid['user_login']]); ?>">
                    <i class="icon-edit middle"></i>
                    <span class="middle"><?= lang('drafts'); ?></span>
                  </a>
                  <a class="dr-menu block" href="<?= getUrlByName('notifications', ['login' => $uid['user_login']]); ?>">
                    <i class="icon-lightbulb middle"></i>
                    <span class="middle"><?= lang('notifications'); ?></span>
                  </a>
                  <a class="dr-menu block" href="<?= getUrlByName('messages', ['login' => $uid['user_login']]); ?>">
                    <i class="icon-mail middle"></i>
                    <span class="middle"><?= lang('messages-m'); ?></span>
                  </a>
                  <a class="dr-menu block" href="<?= getUrlByName('favorites', ['login' => $uid['user_login']]); ?>">
                    <i class="icon-bookmark-empty middle"></i>
                    <span class="middle"><?= lang('favorites'); ?></span>
                  </a>
                  <?php if ($uid['user_trust_level'] > 1) { ?>
                    <a class="dr-menu block" href="<?= getUrlByName('invitations', ['login' => $uid['user_login']]); ?>">
                      <i class="icon-user-add-outline middle"></i>
                      <span class="middle"><?= lang('invites'); ?></span>
                    </a>
                  <?php } ?>
                  <?php if ($uid['user_trust_level'] == 5) { ?>
                    <a class="dr-menu block" href="<?= getUrlByName('admin'); ?>" target="_black">
                      <i class="icon-tools middle"></i>
                      <span class="middle size-14"><?= lang('admin'); ?></span>
                    </a>
                  <?php } ?>
                  <hr>
                  <a class="dr-menu block" href="<?= getUrlByName('logout'); ?>" class="logout" title="<?= lang('sign out'); ?>">
                    <i class="icon-logout middle"></i>
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