<box class="bg-blue-100 dark-bg-black gray dark-gray-300 center">
  <?= Translate::get('not registered'); ?>?
  <form action="<?= getUrlByName('register'); ?>" class="mt15 mb15 block">
    <?= sumbit(Translate::get('create account')); ?>
  </form>
  <i class="bi bi-emoji-wink absolute right0 mr15 bottom0 mb5 text-3xl gray-400"></i>
  <a class="mt15 mb0 gray lowercase block text-sm" href="<?= getUrlByName('login'); ?>">
    <?= Translate::get('sign.in'); ?>
  </a>
</box>