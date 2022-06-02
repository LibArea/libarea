<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?07');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false;
$post   = $data['post'] ?? false;
 ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="body-bg-fon<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?><?php if (Request::getCookie('menuYesNo') == 'menuno') : ?> menuno<?php endif; ?>">
  <header class="d-header<?php if($post || $facet) : ?> choices<?php endif; ?>">

    <div class="wrap">
      <div class="d-header_contents">
      
        <div class="flex">
          <div id="togglemenu" class="mb-none mr10"><i class="bi-list gray-600 text-xl"></i></div>
          <div class="menu__button none mb-block mr10"><i class="bi-list gray-600 text-xl"></i></div>
          <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta.name'); ?></a>
        </div>

        <?php if($post) : ?>
          <div class="d-header-post none">
            <span class="v-line mb-none"></span>
            <a class="mb-none" href="<?= url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']])?>">
              <?= $data['post']['post_title'] ?>
            </a>
          </div>
        <?php endif; ?>
         
        <?php if($facet) : ?>
         <div class="d-header-facet none">
           <span class="v-line mb-none"></span>
           <a class="mb-none" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']])?>">
             <?= Html::image($facet['facet_img'], $facet['facet_title'], 'img-base mr15', 'logo', 'max'); ?>
             <?= $facet['facet_title'];?>
           </a>
           <span class="gray-600 text-sm lowercase mb-none"> - <?= $facet['facet_short_description'];?></span>
         </div>
        <?php endif; ?> 
         
        <div class="box-search mb-none">
          <form class="form" method="get" action="<?= url('search.go'); ?>">
            <input type="text" name="q" autocomplete="off" id="find" placeholder="<?= __('app.find'); ?>" class="search">
          </form>
          <div class="absolute box-shadow bg-white p15 pt0 mt5 br-rd3 none" id="search_items"></div>
        </div>

        <?php if (!UserData::checkActiveUser()) : ?>
          <div class="flex right items-center">
            <div id="toggledark" class="header-menu-item">
              <i class="bi-brightness-high gray-600 text-xl"></i>
            </div>
            <?php if (config('general.invite') == false) : ?>
              <a class=" gray ml45 mr15 mb-mr5 mb-ml5 mb-none block" href="<?= url('register'); ?>">
                <?= __('app.registration'); ?>
              </a>
            <?php endif; ?>
            <a class=" btn btn-outline-primary ml20 pl10 pr10" href="<?= url('login'); ?>">
              <?= __('app.sign_in'); ?>
            </a>
          </div>
        <?php else : ?>

          <div class="flex right ml45 mb-ml0 items-center text-xl">

            <?= Html::addPost($facet); ?>

            <div id="toggledark" class="only-icon ml45 mb-ml20">
              <i class="bi-brightness-high gray-600"></i>
            </div>

            <a class="gray-600 ml45 mb-ml20" href="<?= url('notifications'); ?>">
              <?php $notif = \App\Controllers\NotificationController::setBell(UserData::getUserId()); ?>
              <?php if (!empty($notif)) : ?>
                <?php if ($notif['action_type'] == 1) : ?>
                  <i class="bi-envelope red"></i>
                <?php else : ?>
                  <i class="bi-bell-fill red"></i>
                <?php endif; ?>
              <?php else : ?>
                <i class="bi-bell"></i>
              <?php endif; ?>
            </a>

            <div class="ml45 mb-ml20">
              <div class="trigger">
                <?= Html::image(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base mb-pr0', 'avatar', 'small'); ?>
              </div>
              <ul class="dropdown">
                <?= insert('/_block/navigation/menu', ['type' => $type, 'list' => config('navigation/menu.user')]); ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
        
      </div>
    </div>
  </header>
  <?php if (!UserData::checkActiveUser() && $type == 'main') : ?>
    <div class="box mb-none center">
      <h1><?= config('meta.banner_title'); ?></h1>
      <p><?= config('meta.banner_desc'); ?>...</p>
    </div>
  <?php endif; ?>

  <div id="contentWrapper" class="wrap">

    <?= insert('/menu', ['data' => $data]); ?>