<?php if ($uid['user_id'] == 0) { ?>
  <div class="flex right col-span-4 items-center">
    <div id="toggledark" class="header-menu-item no-mob only-icon p10 ml30 mb-ml-10">
      <i class="bi bi-brightness-high gray-light-2 size-18"></i>
    </div>
    <?php if (Config::get('general.invite') == 0) { ?>
      <a class="register gray size-15 ml30 mr15 block" title="<?= Translate::get('sign up'); ?>" href="<?= getUrlByName('register'); ?>">
        <?= Translate::get('sign up'); ?>
      </a>
    <?php } ?>
    <a class="btn btn-outline-primary ml20" title="<?= Translate::get('sign in'); ?>" href="<?= getUrlByName('login'); ?>">
      <?= Translate::get('sign in'); ?>
    </a>
  </div>
<?php } else { ?>
  <div class="col-span-4">
    <div class="flex right ml30 items-center">

      <?= add_post($facet, $uid['user_id']); ?>

      <div id="toggledark" class="only-icon p10 ml20 mb-ml-10">
        <i class="bi bi-brightness-high gray-light-2 size-18"></i>
      </div>

      <a class="gray-light-2 p10 ml20 mb-ml-10" href="<?= getUrlByName('user.notifications', ['login' => $uid['user_login']]); ?>">
        <?php $notif = \App\Controllers\NotificationsController::setBell($uid['user_id']); ?>
        <?php if (!empty($notif)) { ?>
          <?php if ($notif['notification_action_type'] == 1) { ?>
            <i class="bi bi-envelope size-18 red"></i>
          <?php } else { ?>
            <i class="bi bi-bell-fill size-18 red"></i>
          <?php } ?>
        <?php } else { ?>
          <i class="bi bi-bell mb-size-18 size-18"></i>
        <?php } ?>
      </a>

      <div class="dropbtn relative p10 ml20 mb-ml-10">
        <a class="relative w-auto">
          <?= user_avatar_img($uid['user_avatar'], 'small', $uid['user_login'], 'w34 br-rd-50'); ?>
        </a>
        <div class="dr-menu box-shadow none min-w165 right0 bg-white size-15 br-rd3 p5 absolute">
          <?php foreach (Config::get('menu-header-user') as $menu) { ?>
            <?= $menu['hr'] ?? ''; ?>
            <?php if ($uid['user_trust_level'] >= $menu['tl']) { ?>
              <a class="pt5 pr10 pb5 pl10 block gray bg-hover-light" href="<?= getUrlByName($menu['url'], ['login' => $uid['user_login']]); ?>">
                <i class="<?= $menu['icon']; ?> middle mr5"></i>
                <span class="middle size-14"><?= $menu['name']; ?></span>
              </a>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
<?php }  ?>