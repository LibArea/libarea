<nav class="justify-between mt0 ml0 pl0 t-81 sticky size-15 max-w170">
  <div class="center pt5 mb15 no-mob">
    <?php if ($uid['user_id'] == 0) { ?>
    <a href="/login" class="button block br-rd-5 white">
      <i class="bi bi-plus-lg mr5 middle"></i> <?= lang('create'); ?>
    </a>
    <?php } else { ?>
      <a href="/post/add" class="button block br-rd-5 white">
        <i class="bi bi-plus-lg mr5 middle"></i> <?= lang('create'); ?>
      </a>
    <?php } ?>
  </div>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('feed'); ?>" href="/">
    <i class="bi bi-sort-down middle mr5<?= $sheet == 'feed' ? ' blue' : ''; ?>  size-18"></i>
    <span class="<?= $sheet == 'feed' ? 'blue' : ''; ?>"><?= lang('feed'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('spaces'); ?>" href="<?= getUrlByName('spaces'); ?>">
    <i class="bi bi-command middle mr5<?= $sheet == 'spaces' ? ' blue' : '';  ?> size-18"></i>
    <span class="<?= $sheet == 'spaces' ? 'blue' : ''; ?>"><?= lang('spaces'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('topics'); ?>" href="<?= getUrlByName('topics'); ?>">
    <i class="bi bi-columns-gap middle mr5<?= $sheet == 'topics' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'topics' ? 'blue' : ''; ?>"><?= lang('topics'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('users'); ?>" href="<?= getUrlByName('users'); ?>">
    <i class="bi bi-people middle mr5<?= $sheet == 'users' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'users' ? 'blue' : ''; ?>"><?= lang('users'); ?></span>
  </a>
  <?php if ($uid['user_id'] > 0) { ?>
    <hr>
    <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('favorites'); ?>" href="<?= getUrlByName('favorites', ['login' => $uid['user_login']]); ?>">
      <i class="bi bi-bookmark middle mr5<?= $sheet == 'favorites' ? ' blue' : ''; ?> middle size-18"></i>
      <span class="<?= $sheet == 'favorites' ? 'blue' : ''; ?>"><?= lang('favorites'); ?></span>
    </a>
  <?php } ?>
</nav>