<nav class="justify-between mt10 ml0 pl0 top70 sticky size-14 max-w200">
  <?php foreach (Config::get('menu-left') as  $menu) { ?>
    <a class="pt5 pb5 pl10 flex flex-row items-center gray bg-hover-light<?= $sheet == $menu['item'] ? ' blue' : ''; ?>" 
      <?= $sheet == $menu['item'] ? ' aria-current="page" ' : ''; ?>
      title="<?= $menu['name']; ?>" href="<?= $menu['url']; ?>">
      <i class="<?= $menu['icon']; ?> middle mr10 size-21"></i>
      <?= $menu['name']; ?>
    </a>
  <?php } ?>
  <?php if ($uid['user_id'] > 0) { ?>
    <hr>
    <a class="pt5 pb5 pl10 flex flex-row items-center gray block<?= $sheet == 'favorites' ? ' blue' : ''; ?>" title="<?= Translate::get('favorites'); ?>" href="<?= getUrlByName('user.favorites', ['login' => $uid['user_login']]); ?>">
      <i class="bi bi-bookmark middle mr5 middle size-21"></i>
      <?= Translate::get('my'); ?> 
      <span class="lowercase"><?= Translate::get('favorites'); ?></span>
    </a>
  <?php } ?>
  <?php if ($uid['user_trust_level'] > 4) { ?>
    <a class="pt5 pb5 pl10 flex flex-row items-center black dark-white" href="<?= getUrlByName('admin.users'); ?>">
      <i class="bi bi-person-x middle mr10 middle size-21"></i>
      <span><?= Translate::get('admin'); ?></span>
    </a>
  <?php } ?>
</nav>