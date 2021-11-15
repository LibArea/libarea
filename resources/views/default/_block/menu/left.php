<nav class="justify-between mt10 ml0 pl0 top80 sticky size-15 max-w170">
  <?php foreach (Config::get('menu-left') as  $menu) { ?>
    <a class="pt5 pr10 pb5 pl10 block gray bg-hover-light" title="<?= $menu['name']; ?>" href="<?= $menu['url']; ?>">
      <i class="<?= $menu['icon']; ?> middle mr5<?= $sheet == $menu['item'] ? ' blue' : ''; ?>  size-18"></i>
      <span class="<?= $sheet == $menu['item'] ? 'blue' : ''; ?>">
        <?= $menu['name']; ?>
      </span>
    </a>
  <?php } ?>
  <?php if ($uid['user_id'] > 0) { ?>
    <hr>
    <a class="pt5 pr10 pb5 pl10 gray block" title="<?= Translate::get('favorites'); ?>" href="<?= getUrlByName('favorites', ['login' => $uid['user_login']]); ?>">
      <i class="bi bi-bookmark middle mr5<?= $sheet == 'favorites' ? ' blue' : ''; ?> middle size-18"></i>
      <span class="<?= $sheet == 'favorites' ? 'blue' : ''; ?>"><?= Translate::get('favorites'); ?></span>
    </a>
  <?php } ?>
  <?php if ($uid['user_trust_level'] > 4) { ?>
    <a class="pt5 pr10 pb5 pl10 black block dark-white" href="<?= getUrlByName('admin.users'); ?>">
      <i class="bi bi-person-x middle mr5 middle size-18"></i>
      <span><?= Translate::get('admin'); ?></span>
    </a>
  <?php } ?>
</nav>