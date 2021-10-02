<footer class="col-span-12 wrap pr10 pl10 grid grid-cols-12 bg-gray-800 clear mt15 pt5 pb15 mr20 ml20">
  <div class="col-span-12 wrap white pr10 pl10 size-14 grid grid-cols-12">
    <div class="text-info col-span-3 left no-mob">
      <h4 class="mb5 uppercase font-light"><?= lang('info'); ?></h4>
      <a class="block white mb5" title="<?= lang('spaces'); ?>" href="<?= getUrlByName('spaces'); ?>">
        <?= lang('spaces'); ?>
      </a>
      <a class="block white mb5" title="<?= lang('topics'); ?>" href="<?= getUrlByName('topics'); ?>">
        <?= lang('topics'); ?>
      </a>
      <a class="block white" title="<?= lang('users'); ?>" href="<?= getUrlByName('users'); ?>">
        <?= lang('users'); ?>
      </a>
    </div>
    <div class="text-info col-span-3 no-mob">
      <h4 class="mb5 uppercase font-light"><?= lang('other'); ?></h4>
      <a class="block white mb5" title="<?= lang('all answers'); ?>" href="<?= getUrlByName('answers'); ?>">
        <?= lang('answers-n'); ?>
      </a>
      <a class="block white mb5" title="<?= lang('all comments'); ?>" href="<?= getUrlByName('comments'); ?>">
        <?= lang('comments-n'); ?>
      </a>
      <a class="block white" title="<?= lang('all domains'); ?>" href="<?= getUrlByName('web'); ?>">
        <?= lang('domains'); ?>
      </a>
    </div>
    <div class="text-info col-span-3 no-mob">
      <h4 class="mb5 uppercase font-light"><?= lang('help'); ?></h4>
      <a class="block white mb5" title="<?= lang('info'); ?>" href="<?= getUrlByName('info'); ?>">
        <?= lang('info'); ?>
      </a>
      <a class="block white no-mob" title="<?= lang('privacy'); ?>" href="<?= getUrlByName('info.privacy'); ?>">
        <?= lang('privacy'); ?>
      </a>
    </div>
    <div class="text-info col-span-3 mb-col-12">
      <h4 class="mb5 uppercase font-light"><?= lang('social networks'); ?></h4>
      <a rel="nofollow noopener" class="white" title="DISCORD" href="https://discord.gg/dw47aNx5nU">
        <i class="bi bi-discord size-21 mr10"></i>
      </a>
      <a rel="nofollow noopener" class="white" title="GitHub" href="https://github.com/agoutiDev/agouti">
        <i class="bi bi-github size-21"></i>
      </a>
      <div class="size-13 mt5 mb5 gray-light-2">
        Agouti &copy; <?= date('Y'); ?> — <?= lang('community'); ?>
      </div>
    </div>
  </div>
</footer>