<nav class="justify-between mt10 ml0 pl0 t-81 sticky size-15 max-w170">
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('feed'); ?>" href="/">
    <i class="bi bi-sort-down middle mr5<?= $sheet == 'feed' ? ' blue' : ''; ?>  size-18"></i>
    <span class="<?= $sheet == 'feed' ? 'blue' : ''; ?>"><?= lang('feed'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('topics'); ?>" href="<?= getUrlByName('topics'); ?>">
    <i class="bi bi-columns-gap middle mr5<?= $sheet == 'topics' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'topics' ? 'blue' : ''; ?>"><?= lang('topics'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('users'); ?>" href="<?= getUrlByName('users'); ?>">
    <i class="bi bi-people middle mr5<?= $sheet == 'users' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'users' ? 'blue' : ''; ?>"><?= lang('users'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('all answers'); ?>" href="<?= getUrlByName('answers'); ?>">
    <i class="bi bi-chat-dots middle mr5<?= $sheet == 'users' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'users' ? 'blue' : ''; ?>"><?= lang('answers-n'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('domains'); ?>" href="<?= getUrlByName('web'); ?>">
    <i class="bi bi-link-45deg middle mr5<?= $sheet == 'domains' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'domains' ? 'blue' : ''; ?>"><?= lang('domains'); ?></span>
  </a>
  <?php if ($uid['user_id'] > 0) { ?>
    <hr>
    <a class="pt5 pr10 pb5 pl10 gray block" title="<?= lang('favorites'); ?>" href="<?= getUrlByName('favorites', ['login' => $uid['user_login']]); ?>">
      <i class="bi bi-bookmark middle mr5<?= $sheet == 'favorites' ? ' blue' : ''; ?> middle size-18"></i>
      <span class="<?= $sheet == 'favorites' ? 'blue' : ''; ?>"><?= lang('favorites'); ?></span>
    </a>
  <?php } ?>
</nav>