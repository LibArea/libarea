<main>
  <div class="box">
    <h1 class="title"><?= __('app.password_recovery'); ?></h1>
    <form class="form max-w-sm" action="<?= url('recover.send', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>

      <fieldset>
        <label for="post_title"><?= __('app.email'); ?></label>
        <input type="email" required="" name="email">
      </fieldset>

      <?= insert('/_block/form/captcha'); ?>

      <fieldset>
        <?= Html::sumbit(__('app.reset')); ?>
        <?php if (config('general', 'invite') == false) : ?>
          <span class="mr5 ml15 text-sm"><a href="<?= url('register'); ?>"><?= __('app.registration'); ?></a></span>
        <?php endif; ?>
        <span class="mr5 ml15 text-sm"><a href="<?= url('login'); ?>"><?= __('app.sign_in'); ?></a></span>
      </fieldset>
    </form>
    <p><?= __('app.agree_rules'); ?>.</p>
    <p><?= __('help.recover_info'); ?></p>
  </div>
</main>