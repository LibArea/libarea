<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?' . config('assembly-js-css.version'));
$uri = $data['type'] ?? 'post';
$q = $data['q'];
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body <?php if (Request::getCookie('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>

  <header class="d-header">
    <div class="wrap wrap-max">
      <div class="d-header_contents">

        <div class="flex items-center gray-600 gap-min">
          <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta.name'); ?></a>
        </div>

        <div class="box-search ml20">
          <form method="get" action="<?= url('search.go'); ?>">
            <input type="text" name="q" value="<?= $q; ?>" placeholder="<?= __('search.find'); ?>" class="search">
            <input name="cat" value="<?= $uri; ?>" type="hidden">
            <?= csrf_field() ?>
          </form>
          <div class="search-box none" id="search_items"></div>
        </div>

        <?php if (!UserData::checkActiveUser()) : ?>
          <div class="flex gap-max items-center">
            <div id="toggledark" class="gray-600">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
            </div>
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
  <div class="ml20">
    <ul class="nav inline">
      <li<?php if ($uri == 'post') : ?> class="active" <?php endif; ?>>
        <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=post"><?= __('search.posts'); ?></a>
        </li>
        <li<?php if ($uri == 'answer') : ?> class="active" <?php endif; ?>>
          <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=answer"><?= __('search.answers'); ?></a>
          </li>
          <li<?php if ($uri == 'website') : ?> class="active" <?php endif; ?>>
            <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=website"><?= __('search.websites'); ?></a>
            </li>
    </ul>
  </div>