<div class="br-box-gray bg-blue-100 dark-bg-black dark-br-black gray dark-white p20 mb15 relative center br-rd5">
  <?= Translate::get('not registered'); ?>? 
  <form action="<?= getUrlByName('register'); ?>" class="mt15 mb15 block">
    <?= sumbit(Translate::get('create account')); ?> 
  </form>   
  <i class="bi bi-emoji-wink absolute right0 mr15 bottom0 mb5 size-31 gray-400"></i>
  <a class="mt15 mb0 gray lowercase block size-14" href="<?= getUrlByName('login'); ?>">
    <?= Translate::get('sign in'); ?>
  </a>
</div>