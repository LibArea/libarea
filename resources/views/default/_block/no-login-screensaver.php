<div class="box bg-yellow gray center">
  <?= __('app.not_registered'); ?>?
  <form action="<?= url('register'); ?>" class="m15 block">
    <?= Html::sumbit(__('app.create_account')); ?>
  </form>
  <a class="red lowercase block" href="<?= url('login'); ?>">
    <?= __('app.sign_in'); ?>
  </a>
</div>