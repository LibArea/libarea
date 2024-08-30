<?= insertTemplate('/meta', ['meta' => $meta]); ?>

<body class="item<?php if ($container->cookies()->get('dayNight')->value() == 'dark') : ?> dark<?php endif; ?>">
  <header class="d-header scroll-hide-search">
    <div class="wrap">
      <div class="d-header_contents">

        <div class="flex flex-auto">
          <div class="box-logo">
            <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta', 'name'); ?></a>
          </div>

          <div class="box-search mb-none">
            <form class="form" method="get" action="<?= url('search.go'); ?>">
              <input data-id="category" type="text" name="q" autocomplete="off" id="find" placeholder="<?= __('app.find'); ?>" class="search">
              <input name="cat" value="website" type="hidden">
            </form>
            <div class="box-results none" id="search_items"></div>
          </div>
        </div>

        <?php if (!$container->user()->active()) : ?>
          <div class="flex gap-max items-center mb-mt5">
            <div id="toggledark" class="gray-600">
              <svg class="icon">
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
          <div class="flex gap-max items-center mb-mt5">
            <a id="toggledark" class="gray-600">
			  <svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
			</a>

            <a id="notif" class="gray-600 relative" href="<?= url('notifications'); ?>">
              <svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#bell"></use>
              </svg>
              <span class="number-notif"></span>
            </a>

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