<footer class="w-100 mt15 p15 bg-zinc-800 clear">
  <div class="col-span-12 max-width grid grid-cols-12 mr-auto">
    <div class="col-span-3 left mb-none ml10">
      <h4 class="mt5 mb5 uppercase font-light white"><?= Translate::get('info'); ?></h4>
      <a class="table gray-400 gray-hover text-sm mb5" title="<?= Translate::get('all domains'); ?>" href="<?= getUrlByName('web'); ?>">
        <?= Translate::get('domains'); ?>
      </a>
      <a class="table gray-400 gray-hover text-sm mb5" title="<?= Translate::get('topics'); ?>" href="<?= getUrlByName('topics'); ?>">
        <?= Translate::get('topics'); ?>
      </a>
      <a class="table gray-400 gray-hover text-sm" title="<?= Translate::get('users'); ?>" href="<?= getUrlByName('users.all'); ?>">
        <?= Translate::get('users'); ?>
      </a>
    </div>
    <div class="col-span-3 mb-none">
      <h4 class="mt5 mb5 uppercase font-light white"><?= Translate::get('other'); ?></h4>
      <a class="table gray-400 gray-hover text-sm mb5" title="<?= Translate::get('all answers'); ?>" href="<?= getUrlByName('answers'); ?>">
        <?= Translate::get('answers'); ?>
      </a>
      <a class="table gray-400 gray-hover text-sm mb5" title="<?= Translate::get('all comments'); ?>" href="<?= getUrlByName('comments'); ?>">
        <?= Translate::get('comments'); ?>
      </a>
    </div>
    <div class="col-span-3">
      <h4 class="mt5 mb5 uppercase font-light white"><?= Translate::get('help'); ?></h4>
      <a class="table gray-400 gray-hover text-sm mb5" title="<?= Translate::get('info'); ?>" href="/info/<?= Config::get('facets.page-one'); ?>">
        <?= Translate::get('info'); ?>
      </a>
      <a class="table gray-400 gray-hover text-sm" title="<?= Translate::get('privacy'); ?>" href="/info/<?= Config::get('facets.page-two'); ?>">
        <?= Translate::get('privacy'); ?>
      </a>
    </div>
    <div class="col-span-3 mb-col-12">
      <h4 class="mt5 mb5 uppercase font-light white"><?= Translate::get('social networks'); ?></h4>
      <a rel="nofollow noopener" class="gray-400 gray-hover inline mr10" title="DISCORD" href="https://discord.gg/dw47aNx5nU">
        <i class="bi bi-discord text-2xl"></i>
      </a>
      <a rel="nofollow noopener" class="gray-400 gray-hover inline text-2xl" title="Vkontakte" href="https://vk.com/agouti">
        VK
      </a>
      <a rel="nofollow noopener" class="gray-400 gray-hover inline ml10" title="GitHub" href="https://github.com/agoutiDev/agouti">
        <i class="bi bi-github text-2xl"></i>
      </a>
      <div class="text-sm mt5 mb5 gray-400">
        <?= Config::get('meta.name'); ?>
        &copy; <?= date('Y'); ?> — <?= Translate::get('community'); ?>
      </div>
    </div>
  </div>
</footer>