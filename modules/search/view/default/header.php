<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?12');
$uri = $data['type'] ?? 'all';
$q = $data['q'];
?>

<?= Tpl::insert('/meta', ['meta' => $meta]); ?>

<body <?php if (Request::getCookie('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>
  <header>
    <div class="page-search mb-p10">
      <a class="logo mt30 mb-none" href="<?= url('search'); ?>">
        <?= __('search.name'); ?>
      </a>
      <div class="page-search-right mb-ml0">
        <div data-template="one" id="find tippy">

          <a class="tabs black mr15" href="/">
            <i class="bi-house"></i>
            <?= __('search.to_website'); ?>
          </a>

          <a class="tabs<?php if ($uri == 'all') : ?> active<?php endif; ?>" href="<?= url('search.go'); ?>?q=<?= $q; ?>">
            <?= __('search.all'); ?>
          </a>

          <a class="tabs<?php if ($uri == 'post') : ?> active<?php endif; ?>" href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=post">
            <?= __('search.posts'); ?>
          </a>

          <a class="tabs<?php if ($uri == 'website') : ?> active<?php endif; ?>" href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=website">
            <?= __('search.websites'); ?>
          </a>

          <div class="flex right items-center">
            <div id="toggledark" class="header-menu-item mb-none only-icon mr30 mb-ml10">
              <i class="bi-brightness-high gray-600 text-xl"></i>
            </div>
            <?php if (!UserData::checkActiveUser()) : ?>
              <?php if (config('general.invite') == false) : ?>
                <a class="register gray-600 mr15 mb-ml10 mb-mr5 block" href="<?= url('register'); ?>">
                  <?= __('search.registration'); ?>
                </a>
              <?php endif; ?>
              <a class="gray-600 mr10 ml10" href="<?= url('login'); ?>">
                <?= __('search.sign_in'); ?>
              </a>
            <?php else : ?>
              <div class="mr15 m relative">
                <div class="trigger">
                  <?= UserData::getUserLogin(); ?>
                </div>
                <ul class="dropdown">
                  <?= Tpl::insert('/_block/navigation/menu', ['type' => 'dir', 'list' => config('navigation/menu.user')]); ?>
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