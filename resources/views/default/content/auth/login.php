<main>
  <h1 class="title-max"><?= __('app.authorization'); ?></h1>

  <div class="p15 bg-violet max-w300 mb-none right">
    <?= __('auth.login_info'); ?>
  </div>

  <form action="<?= url('enterLogin'); ?>" method="post">
    <?php csrf_field(); ?>
    <?= insert('/_block/form/login'); ?>

    <?php if (config('general.invite') == false) : ?>
      <a class="ml20 text-sm" href="<?= url('register'); ?>"><?= __('app.registration'); ?></a>
    <?php endif; ?>
    <a class="ml20 text-sm" href="<?= url('recover'); ?>"><?= __('app.forgot_password'); ?>?</a>
  </form>

  <?php if (config('general.invite') == 1) : ?>
    <div class="max-w780 mt20"><?= __('auth.invate_text'); ?></div>
  <?php endif; ?>
  <p><?= __('app.agree_rules'); ?>.</p>
</main>