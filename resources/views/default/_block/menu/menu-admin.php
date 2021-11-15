<nav class="justify-between mt0 ml0 pl0 top80 sticky size-15 max-w170">
  <?php foreach (Config::get('menu-admin') as  $menu) { ?>
    <a class="pt5 pr10 pb5 pl10 block gray bg-hover-light" title="<?= $menu['name']; ?>" href="<?= $menu['url']; ?>">
      <i class="<?= $menu['icon']; ?> middle mr5<?= $sheet == $menu['item'] ? ' blue' : ''; ?>  size-18"></i>
      <span class="<?= $sheet == $menu['item'] ? 'blue' : ''; ?>">
        <?= $menu['name']; ?>
      </span>
    </a>
  <?php } ?>
  <hr>
  <span class="size-14 gray-light-2">
    <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?>
  </span>
</nav>