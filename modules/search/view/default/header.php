<?php
Request::getHead()->addStyles('/assets/css/style.css?02');
$uri = $data['type'] == 'post' ? 'post' : 'website';
?>

<?= Tpl::insert('/meta', ['meta' => $meta]); ?>

<body <?php if (Request::getCookie('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>
  <header>
    <div class="page-search mb-p10">
      <a class="logo mt30 mb-none" href="<?= getUrlByName('search'); ?>">
        <?= __('search'); ?>
      </a>
      <div class="page-search-right mb-ml0">
        <div data-template="one" id="find tippy">

          <a class="tabs black mr15" href="/">
            <i class="bi-house"></i>
            <?= __('to.the.website'); ?>
          </a>

          <a class="tabs<?php if ($uri == 'post') : ?> active<?php endif; ?>" href="<?= getUrlByName('search'); ?>">
            <?= __('posts'); ?>
          </a>

          <a class="tabs<?php if ($uri == 'website') : ?> active<?php endif; ?>" href="<?= getUrlByName('search'); ?>?type=website">
            <?= __('websites'); ?>
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
        <form method="get" action="<?= getUrlByName('search'); ?>">
          <input type="text" name="q" placeholder="<?= __('to.find'); ?>" class="page-search__input">
          <input name="type" value="<?= $uri; ?>" type="hidden">
        </form>
      </div>
    </div>
  </header>