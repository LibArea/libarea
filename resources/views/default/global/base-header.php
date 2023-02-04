<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?14');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false;
$post   = $data['post'] ?? false;
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="general<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?><?php if (Request::getCookie('menuYesNo') == 'menuno') : ?> menuno<?php endif; ?>">
  <header class="d-header<?php if ($post || $facet) : ?> choices<?php endif; ?>">

    <div class="wrap">
      <div class="d-header_contents">

        <div class="flex items-center gray-600 gap-min">
          <div id="togglemenu" class="pointer"><svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#menu"></use>
            </svg></div>
          <div class="menu__button none"><svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#menu"></use>
            </svg></div>
          <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta.name'); ?></a>
        </div>

        <?php if ($post) : ?>
          <div class="d-header-post none">
            <span class="v-line mb-none"></span>
            <a class="mb-none" href="<?= post_slug($post['post_id'], $post['post_slug']) ?>">
              <?= $data['post']['post_title'] ?>
            </a>
          </div>
        <?php endif; ?>

        <?php if ($facet) : ?>
          <div class="d-header-facet none">
            <span class="v-line mb-none"></span>
            <a class="mb-none" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]) ?>">
              <?= Img::image($facet['facet_img'], $facet['facet_title'], 'img-base mr15', 'logo', 'max'); ?>
              <?= $facet['facet_title']; ?>
            </a>
            <span class="gray-600 text-sm lowercase mb-none"> - <?= $facet['facet_short_description']; ?></span>
          </div>
        <?php endif; ?>

        <div class="box-search mb-none">
          <form class="form" method="get" action="<?= url('search.go'); ?>">
            <input type="text" name="q" autocomplete="off" id="find" placeholder="<?= __('app.find'); ?>" class="search">
          </form>
          <div class="absolute box-shadow bg-white p15 br-rd3 none" id="search_items"></div>
        </div>

        <?php if (!UserData::checkActiveUser()) : ?>
          <div class="flex gap-max items-center">
            <a id="toggledark" class="header-menu-item gray-600">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
            </a>
            <?php if (config('general.invite') == false) : ?>
              <a class="gray min-w75 center mb-none block" href="<?= url('register'); ?>">
                <?= __('app.registration'); ?>
              </a>
            <?php endif; ?>
            <a class="btn btn-outline-primary min-w75" href="<?= url('login'); ?>">
              <?= __('app.sign_in'); ?>
            </a>
          </div>
        <?php else : ?>

          <div class="flex gap-max items-center">

            <?= Html::addPost($facet); ?>

            <a id="toggledark" class="gray-600"><svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg></a>

            <a id="notif" class="gray-600 relative" href="<?= url('notifications'); ?>">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#bell"></use>
              </svg>
              <span class="number"></span>
            </a>

            <div class="relative">
              <div class="trigger pointer">
                <?= Img::avatar(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base', 'small'); ?>
              </div>
              <div class="dropdown user">
                <?= insert('/_block/navigation/menu-user', ['list' => config('navigation/menu.user')]); ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </header>
  <?php if (!UserData::checkActiveUser() && $type == 'main') : ?>
    <div class="banner mb-none">
      <h1><?= config('meta.banner_title'); ?></h1>
      <p><?= config('meta.banner_desc'); ?>...</p>
    </div>
  <?php endif; ?>

  <div id="contentWrapper" class="wrap">

    <?php
    $css = '';
    $type =  $data['type'] ?? '';
    if (in_array($type, ['blogs', 'recover'])) {
      $css = ' none';
    }
    ?>

    <nav class="menu__left<?= $css; ?> mb-none">
      <ul class="menu sticky top-sm">
        <?= insert('/_block/navigation/menu', ['type' => $type, 'list' => config('navigation/menu.left')]); ?>
      </ul>
    </nav>