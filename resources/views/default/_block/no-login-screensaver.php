<div class="box bg-blue-100 gray center relative">
  <?= __('app.not_registered'); ?>?
  <form action="<?= url('register'); ?>" class="mt15 mb15 block">
    <?= Html::sumbit(__('app.create_account')); ?>
  </form>
  <a class="mt15 red lowercase block text-sm" href="<?= url('login'); ?>">
    <?= __('app.sign_in'); ?>
  </a>
</div>