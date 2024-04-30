<?php
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="body-minimum<?php if ($container->cookies()->get('dayNight') == 'dark') : ?> dark<?php endif; ?><?php if ($container->cookies()->get('menuYesNo') == 'menuno') : ?> menuno<?php endif; ?>">
  <header class="text-header wrap">
    <div class="d-header_contents justify-between">

      <div class="flex gap-max items-center">
        <a title="<?= __('app.home'); ?>" class="logo" href="/">
          L
        </a>
        <ul class="nav scroll-menu mb-w150">
          <?= insert('/_block/navigation/config/home-nav'); ?>
        </ul>
      </div>

      <?php if (!$container->user()->active()) : ?>
        <div class="flex gap-max items-center">
          <div id="toggledark" class="mb-none">
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

        <div class="flex gap-max items-center">

          <?= Html::addPost($facet); ?>

          <div id="toggledark"><svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#sun"></use>
            </svg></div>

          <a id="notif" class="gray-600 relative" href="<?= url('notifications'); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#bell"></use>
            </svg>
            <span class="number-notif"></span>
          </a>

          <div class="relative">
            <div class="trigger">
              <?= Img::avatar($container->user()->avatar(), $container->user()->login(), 'img-base', 'small'); ?>
            </div>
            <ul class="dropdown user">
              <?= insert('/_block/navigation/config/user-menu'); ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </header>

  <div id="contentWrapper">