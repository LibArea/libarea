<main class="max">
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
        <span class="showPassword">
		  <?= icon('icons', 'eye'); ?>
		</span>
        <div class="help">>= 8 <?= __('app.characters'); ?>...</div>
      </fieldset>

      <fieldset>
        <label for="password_confirm"><?= __('app.password'); ?></label>
        <input name="password_confirm" type="password" required>
      </fieldset>

      <fieldset>
        <input type="hidden" name="invitation_code" value="<?= $data['invate']['invitation_code']; ?>">
        <input type="hidden" name="invitation_id" value="<?= $data['invate']['uid']; ?>">
        <input type="hidden" name="device_id" id="register_device_id" value="">
        <?= Html::sumbit(__('app.registration')); ?>
      </fieldset>
    </form>

    <script nonce="<?= config('main', 'nonce'); ?>">
      (function () {
        var s = document.createElement('script');
        s.src = '/assets/js/device/client.base.min.js';
        s.onload = function () {
          var client = new ClientJS();
          var el = document.getElementById('register_device_id');
          if (el) el.value = client.getFingerprint();
        };
        document.head.appendChild(s);
      })();
    </script>
  </div>
</main>