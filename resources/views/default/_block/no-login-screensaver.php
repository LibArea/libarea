<div class="box bg-blue-100 gray center relative">
  <?= __('app.not_registered'); ?>?
  <form action="<?= url('register'); ?>" class="mt15 mb15 block">
    <?= Html::sumbit(__('app.create_account')); ?>
  </form>
  <i class="bi-emoji-wink absolute right0 mr15 bottom0 mb5 text-3xl gray-600"></i>
  <a class="mt15 mb0 gray lowercase block text-sm" href="<?= url('login'); ?>">
    <?= __('app.sign_in'); ?>
  </a>
</div>