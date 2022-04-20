<?php
Request::getHead()->addStyles('/assets/css/style.css?12');
Request::getHead()->addStyles('/assets/css/catalog.css?12');
?>

<?= Tpl::insert('/meta', ['meta' => $meta]); ?>

<body<?php if (Request::getCookie('dayNight') == 'dark') : ?> class="dark"<?php endif; ?>>
  <header>
    <div class="page-search mb-p10">
      <a class="logo" href="<?= getUrlByName('web'); ?>">
        <?= __('catalog'); ?>
      </a>
      <div class="page-search-right mb-ml0">
        <div data-template="one" id="find tippy">
          <a class="tabs black mr15" href="/">
            <i class="bi-house"></i>
            <?= __('to.the.website'); ?>
          </a>
          <div class="flex right items-center">
            <div id="toggledark" class="header-menu-item mb-none only-icon mr30 mb-ml10">
              <i class="bi-brightness-high gray-600 text-xl"></i>
            </div>
            <?php if (!UserData::checkActiveUser()) : ?>
              <?php if (Config::get('general.invite') == false) : ?>
                <a class="register gray-600 mr15 mb-ml10 mb-mr5 block" href="<?= getUrlByName('register'); ?>">
                  <?= __('registration'); ?>
                </a>
              <?php endif; ?>
              <a class="gray-600 mr10 ml10" href="<?= getUrlByName('login'); ?>">
                <?= __('sign.in'); ?>
              </a>
            <?php else : ?>
              <?php if (UserData::checkAdmin()) : ?>
                <div class="relative mr30 gray-600">
                  <div class="trigger">
                    <?= __('menu'); ?>
                  </div>
                  <ul class="dropdown">
                    <?= Tpl::insert('/_block/navigation/menu', ['type' => 'admin', 'user' => $user, 'list' => Config::get('catalog/menu.user')]); ?>
                  </ul>
                </div>
              <?php endif; ?>
              <a class="<?php if ($data['sheet'] == 'web.bookmarks') { ?>sky <?php } ?>mr30 green" href="<?= getUrlByName('web.bookmarks'); ?>">
                <?= __('favorites'); ?>
              </a>
              <div class="mr15 m relative">
                <div class="trigger">
                  <?= $user['login']; ?>
                </div>
                <ul class="dropdown">
                  <?= Tpl::insert('/_block/navigation/menu', ['type' => 'dir', 'user' => $user, 'list' => Config::get('navigation/menu.user')]); ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <form method="get" action="<?= getUrlByName('search.go'); ?>">
          <input type="text" name="q" placeholder="<?= __('to.find'); ?>" class="page-search__input">
          <input name="cat" value="website" type="hidden">
          <?= csrf_field() ?>
        </form>
      </div>
    </div>
  </header>