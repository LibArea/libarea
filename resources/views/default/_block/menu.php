<nav class="justify-between mt0 ml0 pl0 t-81 sticky">
  <div class="center mb15 no-mob">
    <a href="/post/add" class="button block br-rd-5 white">
      <i class="icon-pencil size-18"></i> <?= lang('create'); ?>
    </a>
  </div>
  <div class="size-15">
    <a class="block mb10" title="<?= lang('feed'); ?>" href="/">
      <i class="icon-air<?= $sheet == 'feed' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
      <span class="<?= $sheet == 'feed' ? 'blue' : 'black'; ?>"><?= lang('feed'); ?></span>
    </a>
    <a class="block mb10" title="<?= lang('spaces'); ?>" href="<?= getUrlByName('spaces'); ?>">
      <i class="icon-infinity<?= $sheet == 'spaces' ? ' blue' : ' gray-light-2';  ?> size-18"></i>
      <span class="<?= $sheet == 'spaces' ? 'blue' : 'black'; ?>"><?= lang('spaces'); ?></span>
    </a>
    <a class="block mb10" title="<?= lang('topics'); ?>" href="<?= getUrlByName('topics'); ?>">
      <i class="icon-clone<?= $sheet == 'topics' ? ' blue' : ' gray-light-2'; ?> size-18"></i>
      <span class="<?= $sheet == 'topics' ? 'blue' : 'black'; ?>"><?= lang('topics'); ?></span>
    </a>
    <a class="block mb10" title="<?= lang('users'); ?>" href="<?= getUrlByName('users'); ?>">
      <i class="icon-user-o<?= $sheet == 'users' ? ' blue' : ' gray-light-2'; ?> size-18"></i>
      <span class="<?= $sheet == 'users' ? 'blue' : 'black'; ?>"><?= lang('users'); ?></span>
    </a>
    <?php if ($uid['user_id'] > 0) { ?>
    <hr>
    <a class="block mb5" title="<?= lang('favorites'); ?>" href="<?= getUrlByName('favorites', ['login' => $uid['user_login']]); ?>">
      <i class="icon-bookmark-empty <?= $sheet == 'favorites' ? ' blue' : ' gray-light-2'; ?> middle size-18"></i>
      <span class="<?= $sheet == 'favorites' ? 'blue' : 'black'; ?>"><?= lang('favorites'); ?></span>
    </a>
    <?php } ?>
  </div>
</nav>