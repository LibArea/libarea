<!DOCTYPE html>
<html lang="ru" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <?php include TEMPLATE_DIR . '/meta-tags.php'; ?>
  <?php getRequestHead()->output(); ?>

  <script src="/assets/js/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="black<?php if (Request::getCookie('dayNight') == 'dark') { ?> dark<?php } ?>">

  <header class="flex justify-content-between align-items-center gray-light-2">
    <div class="flex align-items-center">
      <div>
        <a title="<?= lang('Home'); ?>" class="logo" href="/">LORI<span class="red">UP</span></a>
      </div>
      <div>
        <form class="form" method="post" action="/search">
          <?= csrf_field() ?>
          <input type="text" name="q" id="search" placeholder="Найти..." class="form-search">
        </form>
      </div>
      <div>
        <a class="gray-light-2 ml30 no-mob" title="<?= lang('Spaces'); ?>" href="/spaces">
          <i class="icon-infinity"></i>
          <span class="size-15"><?= lang('Spaces'); ?></span>
        </a>
        <a class="gray-light-2 ml30 no-mob" title="<?= lang('Topics'); ?>" href="/topics">
          <i class="icon-clone"></i>
          <span class="size-15"><?= lang('Topics'); ?></span>
        </a>
      </div>
    </div>
    <div class="flex align-items-center">
      <div class="ml30">
        <span id="toggledark" class="my-color-m">
          <i class="icon-sun"></i>
        </span>
      </div>
      <?php if (!$uid['user_id']) { ?>
        <?php if (!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
          <div class="ml30 register">
            <a class="green size-15" title="<?= lang('Sign up'); ?>" href="/register">
              <?= lang('Sign up'); ?>
            </a>
          </div>
        <?php } ?>
        <div class="ml30 login mr5">
          <a class="gray size-15" title="<?= lang('Sign in'); ?>" href="/login"><?= lang('Sign in'); ?></a>
        </div>
      <?php } else { ?>
        <?php if ($uid['notif']) { ?>
          <div class="ml30 notif">
            <a href="/u/<?= $uid['user_login']; ?>/notifications">
              <?php if ($uid['notif']['notification_action_type'] == 1) { ?>
                <i class="icon-mail red"></i>
              <?php } else { ?>
                <i class="icon-bell red"></i>
              <?php } ?>
            </a>
          </div>
        <?php } ?>

        <div class="ml30 add-post">
          <a title="<?= lang('Add post'); ?>" href="/post/add">
            <i class="icon-plus blue middle"></i>
          </a>
        </div>
        <div class="dropbtn ml30">
          <div class="nick size-15 gray" title="<?= $uid['user_login']; ?>">
            <?= $uid['user_login']; ?>
            <?= user_avatar_img($uid['user_avatar'], 'small', $uid['user_login'], 'ava ml5'); ?>
            <i class="icon-down-dir middle"></i>
          </div>
          <div class="dropdown-menu absolute">
            <span class="st"></span>
            <a class="dr-menu mb5 gray" href="/u/<?= $uid['user_login']; ?>">
              <i class="icon-user-o middle"></i>
              <span class="middle size-13"><?= lang('Profile'); ?></span>
            </a>
            <a class="dr-menu mb5 gray" href="/u/<?= $uid['user_login']; ?>/setting">
              <i class="icon-cog-outline middle"></i>
              <span class="middle size-13"><?= lang('Settings'); ?></span>
            </a>
            <a class="dr-menu mb5 gray" href="/u/<?= $uid['user_login']; ?>/drafts">
              <i class="icon-edit middle"></i>
              <span class="middle size-13"><?= lang('Drafts'); ?></span>
            </a>
            <a class="dr-menu mb5 gray" href="/u/<?= $uid['user_login']; ?>/notifications">
              <i class="icon-lightbulb middle"></i>
              <span class="middle size-13"><?= lang('Notifications'); ?></span>
            </a>
            <a class="dr-menu mb5 gray" href="/u/<?= $uid['user_login']; ?>/messages">
              <i class="icon-mail middle"></i>
              <span class="middle size-13"><?= lang('Messages-m'); ?></span>
            </a>
            <a class="dr-menu mb5 gray" href="/u/<?= $uid['user_login']; ?>/favorite">
              <i class="icon-bookmark-empty middle"></i>
              <span class="middle size-13"><?= lang('Favorites'); ?></span>
            </a>
            <?php if ($uid['user_trust_level'] > 1) { ?>
              <a class="dr-menu mb5 gray" href="/u/<?= $uid['user_login']; ?>/invitation">
                <i class="icon-user-add-outline middle"></i>
                <span class="middle size-13"><?= lang('Invites'); ?></span>
              </a>
            <?php } ?>
            <?php if ($uid['user_trust_level'] == 5) { ?>
              <a class="dr-menu mb5 gray" href="/admin" target="_black">
                <i class="icon-tools middle"></i>
                <span class="middle size-13"><?= lang('Admin'); ?></span>
              </a>
            <?php } ?>
            <hr>
            <a class="dr-menu mb5 gray" href="/logout" class="logout" title="<?= lang('Sign out'); ?>">
              <i class="icon-logout middle"></i>
              <span class="middle size-13"><?= lang('Sign out'); ?></span>
            </a>
          </div>
        </div>
      <?php } ?>
    </div>
  </header>