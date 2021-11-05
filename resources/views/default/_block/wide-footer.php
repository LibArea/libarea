<footer class="col-span-12 wrap pr10 pl10 grid grid-cols-12 bg-gray-800 clear mt15 pt5 pb15 mr20 ml20">
  <div class="col-span-12 wrap white pr10 pl10 size-14 grid grid-cols-12">
    <div class="text-info col-span-3 left no-mob">
      <h4 class="mb5 uppercase font-light"><?= Translate::get('info'); ?></h4>
      <a class="block white mb5" title="<?= Translate::get('all domains'); ?>" href="<?= getUrlByName('web'); ?>">
        <?= Translate::get('domains'); ?>
      </a>
      <a class="block white mb5" title="<?= Translate::get('topics'); ?>" href="<?= getUrlByName('topics'); ?>">
        <?= Translate::get('topics'); ?>
      </a>
      <a class="block white" title="<?= Translate::get('users'); ?>" href="<?= getUrlByName('users'); ?>">
        <?= Translate::get('users'); ?>
      </a>
    </div>
    <div class="text-info col-span-3 no-mob">
      <h4 class="mb5 uppercase font-light"><?= Translate::get('other'); ?></h4>
      <a class="block white mb5" title="<?= Translate::get('all answers'); ?>" href="<?= getUrlByName('answers'); ?>">
        <?= Translate::get('answers'); ?>
      </a>
      <a class="block white mb5" title="<?= Translate::get('all comments'); ?>" href="<?= getUrlByName('comments'); ?>">
        <?= Translate::get('comments'); ?>
      </a>
    </div>
    <div class="text-info col-span-3 no-mob">
      <h4 class="mb5 uppercase font-light"><?= Translate::get('help'); ?></h4>
      <a class="block white mb5" title="<?= Translate::get('info'); ?>" href="<?= getUrlByName('info'); ?>">
        <?= Translate::get('info'); ?>
      </a>
      <a class="block white no-mob" title="<?= Translate::get('privacy'); ?>" href="<?= getUrlByName('info.privacy'); ?>">
        <?= Translate::get('privacy'); ?>
      </a>
    </div>
    <div class="text-info col-span-3 mb-col-12">
      <h4 class="mb5 uppercase font-light"><?= Translate::get('social networks'); ?></h4>
      <a rel="nofollow noopener" class="white inline mr10" title="DISCORD" href="https://discord.gg/dw47aNx5nU">
        <i class="bi bi-discord size-21"></i>
      </a>
      <a rel="nofollow noopener" class="white inline size-21" title="Vkontakte" href="https://vk.com/agouti">
        VK
      </a>
      <a rel="nofollow noopener" class="white inline ml10" title="GitHub" href="https://github.com/agoutiDev/agouti">
        <i class="bi bi-github size-21"></i>
      </a>
      <div class="size-13 mt5 mb5 gray-light-2">
        Agouti &copy; <?= date('Y'); ?> — <?= Translate::get('community'); ?>
      </div>
    </div>
  </div>
</footer>