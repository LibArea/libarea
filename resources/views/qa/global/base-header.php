<?php
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body <?php if ($container->cookies()->get('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>

  <header class="bg-white mb10">
    <div class="br-bottom wrap mb-none items-center flex gap mb5">
      <a class="p5 black text-sm" href="/topics">
        <svg class="icon small">
          <use xlink:href="/assets/svg/icons.svg#hash"></use>
        </svg> <?= __('app.topics'); ?>
      </a>
      <a class="black text-sm" href="/blogs">
        <svg class="icon small">
          <use xlink:href="/assets/svg/icons.svg#post"></use>
        </svg> <?= __('app.blogs'); ?>
      </a>
      <a class="black text-sm" href="/users">
        <svg class="icon small">
          <use xlink:href="/assets/svg/icons.svg#users"></use>
        </svg> <?= __('app.users'); ?>
      </a>
      <a class="black text-sm" href="/web">
        <svg class="icon small">
          <use xlink:href="/assets/svg/icons.svg#link"></use>
        </svg> <?= __('app.catalog'); ?>
      </a>
      <a class="black text-sm" href="/search">
        <svg class="icon small">
          <use xlink:href="/assets/svg/icons.svg#search"></use>
        </svg> <?= __('app.search'); ?>
      </a>
    </div>

    <div class="wrap items-center flex justify-between mb-mt5">
      <div class="flex items-center" id="find">
        <a title="<?= __('app.home'); ?>" class="logo" href="/">
          <?= config('meta', 'name'); ?>
        </a>
      </div>

      <?= insert('/_block/navigation/user-bar-header', ['facet_id' => $facet['facet_id'] ?? false]); ?> 
    </div>
  </header>
  <div id="contentWrapper" class="wrap">