<nav class="justify-between mt10 ml0 pl0 t-81 sticky size-15 max-w170">
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= Translate::get('feed'); ?>" href="/">
    <i class="bi bi-sort-down middle mr5<?= $sheet == 'feed' ? ' blue' : ''; ?>  size-18"></i>
    <span class="<?= $sheet == 'feed' ? 'blue' : ''; ?>"><?= Translate::get('feed'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= Translate::get('topics'); ?>" href="<?= getUrlByName('topics'); ?>">
    <i class="bi bi-columns-gap middle mr5<?= $sheet == 'topics-all' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'topics-all' ? 'blue' : ''; ?>"><?= Translate::get('topics'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= Translate::get('users'); ?>" href="<?= getUrlByName('users'); ?>">
    <i class="bi bi-people middle mr5<?= $sheet == 'users' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'users' ? 'blue' : ''; ?>"><?= Translate::get('users'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= Translate::get('search'); ?>" href="<?= getUrlByName('search'); ?>">
    <i class="bi bi-search middle mr5 size-18"></i>
    <span><?= Translate::get('search'); ?></span>
  </a> 
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= Translate::get('all answers'); ?>" href="<?= getUrlByName('answers'); ?>">
    <i class="bi bi-chat-dots middle mr5<?= $sheet == 'answers' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'answers' ? 'blue' : ''; ?>"><?= Translate::get('answers'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 gray block" title="<?= Translate::get('domains'); ?>" href="<?= getUrlByName('web'); ?>">
    <i class="bi bi-link-45deg middle mr5<?= $sheet == 'domains' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'domains' ? 'blue' : ''; ?>"><?= Translate::get('domains'); ?></span>
  </a>
  <?php if ($uid['user_id'] > 0) { ?>
    <hr>
    <a class="pt5 pr10 pb5 pl10 gray block" title="<?= Translate::get('favorites'); ?>" href="<?= getUrlByName('favorites', ['login' => $uid['user_login']]); ?>">
      <i class="bi bi-bookmark middle mr5<?= $sheet == 'favorites' ? ' blue' : ''; ?> middle size-18"></i>
      <span class="<?= $sheet == 'favorites' ? 'blue' : ''; ?>"><?= Translate::get('favorites'); ?></span>
    </a>
  <?php } ?>
  <?php if ($uid['user_trust_level'] > 4) { ?>
    <a class="pt5 pr10 pb5 pl10 black block" href="<?= getUrlByName('admin.users'); ?>">
      <i class="bi bi-person-x middle mr5 middle size-18"></i>
      <span><?= Translate::get('admin'); ?></span>
    </a>
  <?php } ?>
</nav>