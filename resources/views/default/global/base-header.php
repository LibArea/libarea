<?php

/**
 * @var $container App\Bootstrap\ContainerInterface 
 */

$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false;
$post   = $data['post'] ?? false;
?>

<?= insert('/meta', ['meta' => $meta, 'type' => $type]); ?>

<body class="<?= $type; ?><?php if ($container->cookies()->get('dayNight')->value() == 'dark') : ?> dark<?php endif; ?>">
  <header class="d-header<?php if ($post || $facet) : ?> scroll-hide-search<?php endif; ?>">
    <div class="wrap">
      <div class="d-header_contents">

        <div class="flex flex-auto">
          <div class="box-logo">
            <svg class="icon large menu__button none">
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

          <div class="box-search relative">
            <form class="form" method="get" action="<?= url('search.go'); ?>">
              <input data-id="topic" type="text" name="q" autocomplete="off" id="find" placeholder="<?= __('app.find'); ?>" class="search">
            </form>
            <div class="box-results none" id="search_items"></div>
          </div>
        </div>

        <?= insert('/_block/navigation/user-bar-header', ['facet_id' => $facet['facet_id'] ?? false]); ?>
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
    <div class="nav-sidebar">
      <nav class="menu__left sticky mb-none">
        <ul class="menu top-sm">
          <?= insert('/_block/navigation/config/left-menu', ['type' => $type, 'topics_user' => $topics_user]); ?>
        </ul>
        <footer>
          <b class="uppercase-box ml10"><?= config('meta', 'name'); ?></b>
          <ul class="menu">
            <?= insert('/global/nav-footer'); ?>
          </ul>
          <div class="ml10 mb5 text-sm gray-600">
            <?= config('meta', 'name'); ?> &copy; <?= date('Y'); ?>
            <span class="mb-none">— <?= __('app.community'); ?></span>
          </div>
        </footer>
      </nav>
    </div>
    <div class="main-container">