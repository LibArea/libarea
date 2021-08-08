<!DOCTYPE html>
<html lang="ru" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <?php include TEMPLATE_DIR . '/meta-tags.php'; ?>
  <?php getRequestHead()->output(); ?>

  <script src="/assets/js/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="black<?php if (Request::getCookie('dayNight') == 'dark') { ?> dark<?php } ?>">

  <header class="flex justify-content-between align-items-center gray">
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
        <a class="gray nav no-mob" title="<?= lang('Topics'); ?>" href="/topics">
          <i class="light-icon-layers-subtract"></i>
        </a>
        <a class="gray nav no-mob" title="<?= lang('Spaces'); ?>" href="/spaces">
          <i class="light-icon-infinity"></i>
        </a>
      </div>
    </div>
    <div class="flex align-items-center">
      <div class="nav">
        <span id="toggledark" class="my-color-m">
          <i class="light-icon-brightness-up"></i>
        </span>
      </div>
      <?php if (!$uid['id']) { ?>
        <?php if (!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
          <div class="nav register">
            <a class="green" title="<?= lang('Sign up'); ?>" href="/register">
              <?= lang('Sign up'); ?>
            </a>
          </div>
        <?php } ?>
        <div class="nav no-pc login">
          <a class="gray" title="<?= lang('Sign in'); ?>" href="/login"><?= lang('Sign in'); ?></a>
        </div>
      <?php } else { ?>
        <?php if ($uid['notif']) { ?>
          <div class="nav notif">
            <a href="/u/<?= $uid['login']; ?>/notifications">
              <?php if ($uid['notif']['action_type'] == 1) { ?>
                <i class="light-icon-mail-opened red"></i>
              <?php } else { ?>
                <i class="light-icon-bell red"></i>
              <?php } ?>
            </a>
          </div>
        <?php } ?>

        <div class="nav add-post">
          <a title="<?= lang('Add post'); ?>" href="/post/add">
            <i class="light-icon-plus blue middle"></i>
          </a>
        </div>
        <div class="dropbtn nav">
          <div class="nick gray" title="<?= $uid['login']; ?>">
            <?= $uid['login']; ?>
            <?= user_avatar_img($uid['avatar'], 'small', $uid['login'], 'ava ml5'); ?>
            <i class="light-icon-chevron-down middle"></i>
          </div>
          <div class="dropdown-menu absolute">
            <span class="st"></span>
            <a class="dr-menu gray" href="/u/<?= $uid['login']; ?>">
              <i class="light-icon-user middle"></i>
              <span class="middle size-13"><?= lang('Profile'); ?></span>
            </a>
            <a class="dr-menu gray" href="/u/<?= $uid['login']; ?>/setting">
              <i class="light-icon-settings middle"></i>
              <span class="middle size-13"><?= lang('Settings'); ?></span>
            </a>
            <a class="dr-menu gray" href="/u/<?= $uid['login']; ?>/drafts">
              <i class="light-icon-note middle"></i>
              <span class="middle size-13"><?= lang('Drafts'); ?></span>
            </a>
            <a class="dr-menu gray" href="/u/<?= $uid['login']; ?>/notifications">
              <i class="light-icon-notification middle"></i>
              <span class="middle size-13"><?= lang('Notifications'); ?></span>
            </a>
            <a class="dr-menu gray" href="/u/<?= $uid['login']; ?>/messages">
              <i class="light-icon-mail middle"></i>
              <span class="middle size-13"><?= lang('Messages-m'); ?></span>
            </a>
            <a class="dr-menu gray" href="/u/<?= $uid['login']; ?>/favorite">
              <i class="light-icon-bookmark middle"></i>
              <span class="middle size-13"><?= lang('Favorites'); ?></span>
            </a>
            <?php if ($uid['trust_level'] > 1) { ?>
              <a class="dr-menu gray" href="/u/<?= $uid['login']; ?>/invitation">
                <i class="light-icon-wind middle"></i>
                <span class="middle size-13"><?= lang('Invites'); ?></span>
              </a>
            <?php } ?>
            <?php if ($uid['trust_level'] == 5) { ?>
              <a class="dr-menu gray" href="/admin" target="_black">
                <i class="light-icon-key middle"></i>
                <span class="middle size-13"><?= lang('Admin'); ?></span>
              </a>
            <?php } ?>
            <hr>
            <a class="dr-menu gray" href="/logout" class="logout" title="<?= lang('Sign out'); ?>">
              <i class="light-icon-logout middle"></i>
              <span class="middle size-13"><?= lang('Sign out'); ?></span>
            </a>
          </div>
        </div>
      <?php } ?>
    </div>
  </header>