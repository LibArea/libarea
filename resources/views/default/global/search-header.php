<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?7');
$uri = $data['type'] ?? 'post';
$q = $data['q'];
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body <?php if (Request::getCookie('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>
  <header>
    <div class="page-search gap mb-p10">
      <a class="item-logo mb-none" href="<?= url('search'); ?>">
        <?= __('search.name'); ?>
      </a>
      <div class="w-100">
        <div data-template="one" class="flex justify-between" id="find">

          <ul class="nav inline">
            <li><a href="/"><svg class="icons">
                  <use xlink:href="/assets/svg/icons.svg#home"></use>
                </svg> <span class="mb-none"><?= __('search.on_website'); ?></span></a></li>
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

          <div class="flex right gap-max items-center mb5">
            <div id="toggledark" class="header-menu-item mb-none only-icon">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
            </div>
            <?php if (!UserData::checkActiveUser()) : ?>
              <?php if (config('general.invite') == false) : ?>
                <a class="register gray-600 block mb-none" href="<?= url('register'); ?>">
                  <?= __('search.registration'); ?>
                </a>
              <?php endif; ?>
              <a class="gray-600 mb-none" href="<?= url('login'); ?>">
                <?= __('search.sign_in'); ?>
              </a>
            <?php else : ?>
              <div class="relative">
                <div class="trigger pointer">
                  <?= Img::avatar(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base', 'small'); ?>
                </div>
                <ul class="dropdown user">
                  <?= insert('/_block/navigation/menu-user', ['type' => 'dir', 'list' => config('navigation/menu.user')]); ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <form method="get" action="<?= url('search.go'); ?>">
          <input type="text" name="q" value="<?= $q; ?>" placeholder="<?= __('search.find'); ?>" class="page-search__input">
          <input name="cat" value="<?= $uri; ?>" type="hidden">
          <?= csrf_field() ?>
        </form>
      </div>
    </div>
  </header>