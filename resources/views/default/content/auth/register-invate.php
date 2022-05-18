<main class="box w-100">
  <div class="pl20">
    <h1><?= __('app.reg_invite'); ?></h1>
    <form class="max-w300" id="registerInvate" method="post">
      <?php csrf_field(); ?>

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
          <span class="showPassword absolute gray-600 right5 mt5"><i class="bi-eye"></i></span>
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

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('register.add'),
    'redirect'  => url('login'),
    'success'   => __('msg.check_your_email'),
    'id'        => 'form#registerInvate'
  ]
); ?>