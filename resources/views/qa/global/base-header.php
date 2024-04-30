<?php
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body <?php if ($container->cookies()->get('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>

  <header class="bg-white mb10">
    <div class="br-bottom wrap mb-none items-center flex gap mb5">
      <a class="p5 black text-xs" href="/topics">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#hash"></use>
        </svg> <?= __('app.topics'); ?>
      </a>
      <a class="black text-xs" href="/blogs">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#post"></use>
        </svg> <?= __('app.blogs'); ?>
      </a>
      <a class="black text-xs" href="/users">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#users"></use>
        </svg> <?= __('app.users'); ?>
      </a>
      <a class="black text-xs" href="/web">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#link"></use>
        </svg> <?= __('app.catalog'); ?>
      </a>
      <a class="black text-xs" href="/search">
        <svg class="icons icon-small">
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

      <?php if (!$container->user()->active()) : ?>
        <div class="flex gap-max items-center">
          <div id="toggledark" class="gray-600 mb-none">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#sun"></use>
            </svg>
          </div>
          <?php if (config('general', 'invite') == false) : ?>
            <a class="gray center block" href="<?= url('register'); ?>">
              <?= __('app.registration'); ?>
            </a>
          <?php endif; ?>
          <a class="btn btn-outline-primary" href="<?= url('login'); ?>">
            <?= __('app.sign_in'); ?>
          </a>
        </div>
      <?php else : ?>
        <div>
          <div class="flex gap-max items-center">

            <?= Html::addPost($facet); ?>

            <div id="toggledark" class="only-icon">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
            </div>

            <a id="notif" class="gray-600 relative" href="<?= url('notifications'); ?>">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#bell"></use>
              </svg>
              <span class="number-notif"></span>
            </a>

            <div class="relative">
              <div class="trigger mb5">
                <?= Img::avatar($container->user()->avatar(), $container->user()->login(), 'img-base', 'small'); ?>
              </div>
              <ul class="dropdown user">
                <?= insert('/_block/navigation/config/user-menu'); ?>
              </ul>
            </div>

          </div>
        </div>
      <?php endif;  ?>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">