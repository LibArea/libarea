<?php

/**
 * @var $container App\Bootstrap\ContainerInterface 
 */

$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false;
$post   = $data['post'] ?? false;
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="general<?php if ($container->cookies()->get('dayNight')->value() == 'dark') : ?> dark<?php endif; ?><?php if ($container->cookies()->get('menuYesNo')->value() == 'menuno') : ?> menuno<?php endif; ?>">
  <header class="d-header<?php if ($post || $facet) : ?> scroll-hide-search<?php endif; ?>">
    <div class="wrap">
      <div class="d-header_contents">

        <div class="flex flex-auto">
          <div class="box-logo">
            <svg id="togglemenu" class="icons pointer icon-bold">
              <use xlink:href="/assets/svg/icons.svg#menu"></use>
            </svg>

            <svg class="icons menu__button none icon-bold">
              <use xlink:href="/assets/svg/icons.svg#menu"></use>
            </svg>

            <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta', 'name'); ?></a>
          </div>

          <?php if ($post) : ?>
            <div class="d-header-post none">
              <span class="v-line mb-none"></span>
              <a class="mb-none" href="<?= post_slug($post['post_id'], $post['post_slug']) ?>">
                <?= $data['post']['post_title'] ?>
              </a>
            </div>
          <?php endif; ?>

          <?php
          $facet_type = $facet['facet_type'] ?? false;
          $facetIcon = $facet_type == 'topic' ? $facet : false;
          if ($facetIcon) : ?>
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
              <input data-id="topic" type="text" name="q" autocomplete="off" id="find" placeholder="<?= __('app.find'); ?>" class="search">
            </form>
            <div class="box-results none" id="search_items"></div>
          </div>
        </div>

        <?php if (!$container->user()->active()) : ?>
          <div class="flex gap-max items-center">
            <div id="toggledark" class="gray-600">
              <svg class="icons icon-bold">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
            </div>
            <?php if (config('general', 'invite') == false) : ?>
              <a class="gray center mb-none block" href="<?= url('register'); ?>">
                <?= __('app.registration'); ?>
              </a>
            <?php endif; ?>
            <a class="btn btn-outline-primary" href="<?= url('login'); ?>">
              <?= __('app.sign_in'); ?>
            </a>
          </div>
        <?php else : ?>
          <div class="flex gap-max items-center">

            <?= Html::addPost($facet['facet_id'] ?? false); ?>

            <a id="toggledark" class="gray-600">
			  <svg class="icons icon-bold">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
			</a>

            <div class="relative">
              <div id="el_notif" class="none"></div>
              <a id="notif" class="add-notif gray-600 relative">
                <svg class="icons icon-bold">
                  <use xlink:href="/assets/svg/icons.svg#bell"></use>
                </svg>
                <span class="number-notif"></span>
              </a>
            </div>

            <div class="relative">
              <div class="trigger pointer">
                <?= Img::avatar($container->user()->avatar(), $container->user()->login(), 'img-base', 'small'); ?>
              </div>
              <div class="dropdown user">
                <?= insert('/_block/navigation/config/user-menu'); ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </header>
  <?php if (!$container->user()->active() && $type == 'main') : ?>
    <div class="banner mb-none<?php if ($container->cookies()->get('dayNight') == 'dark') : ?> none<?php endif; ?>">
      <h1><?= config('meta', 'banner_title'); ?></h1>
      <p><?= config('meta', 'banner_desc'); ?>...</p>
    </div>
  <?php endif; ?>

  <div id="contentWrapper" class="wrap">
    <nav class="menu__left mb-none">
      <ul class="menu sticky top-sm">
        <?= insert('/_block/navigation/config/left-menu', ['type' => $type, 'topics_user' => $topics_user]); ?>
      </ul>
    </nav>