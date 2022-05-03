<main class="col-two">
  <div class="box">
    <h1 class="mt0 mb10 text-2xl font-normal"><?= __('app.invite'); ?></h1>
    <form class="" action="/invite" method="post">
      <?php csrf_field(); ?>
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
    <?php if (config('general.invite') == true) : ?>
      <?= __('app.invate_text'); ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box bg-violet text-sm">
    <?= __('app.invited_you'); ?>
  </div>
</aside>