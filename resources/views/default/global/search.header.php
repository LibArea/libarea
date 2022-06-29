<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?014');
$uri = $data['type'] ?? 'post';
$q = $data['q'];
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body <?php if (Request::getCookie('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>
  <header>
    <div class="page-search mb-p10">
      <a class="item-logo mb-none" href="<?= url('search'); ?>">
        <?= __('search.name'); ?>
      </a>
      <div class="page-search-right mb-ml0">
        <div data-template="one" class="flex justify-between" id="find tippy">

          <ul class="nav inline">
          <li><a href="/"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#home"></use></svg> <?= __('search.on_website'); ?></a></li>
         <li<?php if ($uri == 'post') : ?> class="active"<?php endif; ?>>
           <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=post"><?= __('search.posts'); ?></a>
         </li>
         <li<?php if ($uri == 'website') : ?> class="active"<?php endif; ?>>
           <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=website"><?= __('search.websites'); ?></a>
         </li>
          </ul>
          
          <div class="flex right gap-max items-center">
            <div id="toggledark" class="header-menu-item mb-none only-icon">
              <svg class="icons"><use xlink:href="/assets/svg/icons.svg#sun"></use></svg>
            </div>
            <?php if (!UserData::checkActiveUser()) : ?>
              <?php if (config('general.invite') == false) : ?>
                <a class="register gray-600 block" href="<?= url('register'); ?>">
                  <?= __('search.registration'); ?>
                </a>
              <?php endif; ?>
              <a class="gray-600" href="<?= url('login'); ?>">
                <?= __('search.sign_in'); ?>
              </a>
            <?php else : ?>
              <div class="relative">
                <div class="trigger mr5">
                  <?= UserData::getUserLogin(); ?>
                </div>
                <ul class="dropdown">
                  <?= insert('/_block/navigation/menu', ['type' => 'dir', 'list' => config('navigation/menu.user')]); ?>
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