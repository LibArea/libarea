<?php
$uri = $data['type'] ?? 'post';
$q = $data['q'];
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body <?php if ($container->cookies()->get('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>

  <header class="d-header">
    <div class="wrap">
      <div class="d-header_contents">

        <div class="flex flex-auto">
          <div class="box-logo">
            <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta', 'name'); ?></a>
          </div>

          <div class="box-search">
            <form method="get" action="<?= url('search.go'); ?>">
              <input type="text" name="q" value="<?= $q; ?>" placeholder="<?= __('search.find'); ?>" class="search">
              <input name="cat" value="<?= $uri; ?>" type="hidden">
              <?= $container->csrf()->field(); ?>
            </form>
            <div class="box-results none" id="search_items"></div>
          </div>
        </div>

        <?php if (!$container->user()->active()) : ?>
          <div class="flex gap-max items-center">
            <div id="toggledark" class="gray-600">
              <svg class="icons">
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
            <a id="toggledark" class="gray-600 mb-none"><svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg></a>

            <a id="notif" class="gray-600 relative mb-none" href="<?= url('notifications'); ?>">
              <svg class="icons">
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

  <div id="contentWrapper" class="wrap mb20">
    <ul class="nav inline ml10">
      <li<?php if ($uri == 'post') : ?> class="active" <?php endif; ?>>
        <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=post"><?= __('search.posts'); ?></a>
        </li>
        <li<?php if ($uri == 'comment') : ?> class="active" <?php endif; ?>>
          <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=comment"><?= __('search.comments'); ?></a>
          </li>
          <li<?php if ($uri == 'website') : ?> class="active" <?php endif; ?>>
            <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=website"><?= __('search.websites'); ?></a>
            </li>
    </ul>
  </div>