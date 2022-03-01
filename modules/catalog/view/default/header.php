<?php
Request::getHead()->addStyles('/assets/css/style.css?12');
Request::getHead()->addStyles('/assets/css/catalog.css?v12');
?>

<?= Tpl::insert('meta', ['meta' => $meta]); ?>

<body<?php if (Request::getCookie('dayNight') == 'dark') { ?>class="dark" <?php } ?>>
  <header>
    <div class="page-search">
      <a class="logo black" href="<?= getUrlByName('web'); ?>">
        <?= Translate::get('catalog'); ?>
      </a>
      <div class="page-search-right">
        <div data-template="one" id="find tippy">
          <a class="tabs black mr15" href="/">
            <i class="bi bi-house"></i>
            <?= Translate::get('to the website'); ?>
          </a>
          <div class="flex right col-span-4 items-center">
            <div id="toggledark" class="header-menu-item mb-none only-icon mr30 mb-ml10">
              <i class="bi bi-brightness-high gray-400 text-xl"></i>
            </div>
            <?php if (!UserData::checkActiveUser()) { ?>
              <?php if (Config::get('general.invite') == false) { ?>
                <a class="register gray-400 mr15 mb-ml10 mb-mr5 block" href="<?= getUrlByName('register'); ?>">
                  <?= Translate::get('registration'); ?>
                </a>
              <?php } ?>
              <a class="gray-400 mr10 ml10" href="<?= getUrlByName('login'); ?>">
                <?= Translate::get('sign.in'); ?>
              </a>
            <?php } else { ?>
              <?php if (UserData::checkAdmin()) { ?>
                <div class="relative mr30 gray-400">
                  <div class="trigger">
                    <?= Translate::get('menu'); ?>
                  </div>
                  <ul class="dropdown">
                    <?= tabs_nav(
                      'menu',
                      'admin',
                      $user,
                      $pages = Config::get('catalog/menu.user')
                    ); ?>
                  </ul>
                </div>
              <?php } ?>
              <a class="<?php if ($data['sheet'] == 'web.bookmarks') { ?>sky-500 <?php } ?>mr30 green-600" href="<?= getUrlByName('web.bookmarks'); ?>">
                <?= Translate::get('favorites'); ?>
              </a>
              <div class="mr15 m relative">
                <div class="trigger">
                  <?= $user['login']; ?>
                </div>
                <ul class="dropdown">
                  <?= tabs_nav(
                    'menu',
                    'dir',
                    $user,
                    $pages = Config::get('menu.user')
                  ); ?>
                </ul>
              </div>
            <?php } ?>
          </div>
        </div>
        <form method="get" action="<?= getUrlByName('search'); ?>">
          <input type="text" name="q" placeholder="<?= Translate::get('to find'); ?>" class="page-search__input">
          <input name="type" value="website" type="hidden">
        </form>
      </div>
    </div>
  </header>