<main>
  <div class="box">
    <h1><?= __('app.reg_invite'); ?></h1>
    <form class="max-w-sm" action="<?= url('register.add', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>

      <fieldset>
        <label for="login"><?= __('app.nickname'); ?></label>
        <input name="login" type="text" required>
        <div class="help">>= 3 <?= __('app.characters'); ?> (<?= __('app.english'); ?>)</div>
      </fieldset>

      <fieldset>
        <label for="email"><?= __('app.email'); ?></label>
        <input name="email" type="email" value="<?= $data['invate']['invitation_email']; ?>" readonly>
        <div class="help"><?= __('app.work_email'); ?>...</div>
      </fieldset>

      <fieldset>
        <label for="password"><?= __('app.password'); ?></label>
        <input id="password" name="password" type="password" required>
        <span class="showPassword"><svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#eye"></use>
          </svg></span>
        <div class="help">>= 8 <?= __('app.characters'); ?>...</div>
      </fieldset>

      <fieldset>
        <label for="password_confirm"><?= __('app.password'); ?></label>
        <input name="password_confirm" type="password" required>
      </fieldset>

      <fieldset>
        <input type="hidden" name="invitation_code" value="<?= $data['invate']['invitation_code']; ?>">
        <input type="hidden" name="invitation_id" value="<?= $data['invate']['uid']; ?>">
        <?= Html::sumbit(__('app.registration')); ?>
      </fieldset>
    </form>
  </div>
</main>