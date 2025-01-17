<main>
  <div class="box">
    <h1 class="title"><?= __('app.authorization'); ?></h1>

    <div class="gray-600 text-sm mb20"><?= __('auth.login_info'); ?></div> 

    <form class="mt20 mb20" action="<?= url('authorization', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/login'); ?>

      <?php if (config('general', 'invite') == false) : ?>
        <a class="ml20 text-sm" href="<?= url('register'); ?>"><?= __('app.registration'); ?></a>
      <?php endif; ?>
      <a class="ml20 text-sm" href="<?= url('recover'); ?>"><?= __('app.forgot_password'); ?>?</a>
    </form>

    <?php if (config('general', 'invite') == 1) : ?>
      <div class="max-w-md mt20"><?= __('auth.invate_text'); ?></div>
    <?php endif; ?>
    <p><?= __('app.agree_rules'); ?>.</p>
  </div>
</main>