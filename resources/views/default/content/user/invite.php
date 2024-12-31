<main>
  <div class="box">
    <h1 class="title"><?= __('app.invite'); ?></h1>
    <form action="/invite" method="post">
      <?= $container->csrf()->field(); ?>
      <fieldset>
        <label for="invite"><?= __('app.code'); ?></label>
        <input type="text" name="invite" id="invite">
      </fieldset>
      <fieldset>
        <?= Html::sumbit(__('app.sign_in')); ?>
        <span class="ml15 text-sm"><a href="<?= url('recover'); ?>">
            <?= __('app.forgot_password'); ?>?</a>
        </span>
      </fieldset>
    </form>
    <?php if (config('general', 'invite') == true) : ?>
      <?= __('auth.invate_text'); ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('app.invited_you'); ?>
  </div>
</aside>