<div class="box bg-blue-100 gray center relative">
  <?= __('not.registered'); ?>?
  <form action="<?= url('register'); ?>" class="mt15 mb15 block">
    <?= Html::sumbit(__('create.account')); ?>
  </form>
  <i class="bi-emoji-wink absolute right0 mr15 bottom0 mb5 text-3xl gray-600"></i>
  <a class="mt15 mb0 gray lowercase block text-sm" href="<?= url('login'); ?>">
    <?= __('sign.in'); ?>
  </a>
</div>