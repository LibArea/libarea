<main class="max-w780 mr-auto box">
  <h1 class="center"><?= __('app.password_recovery'); ?></h1>
  <div class="box wide">
    <form action="<?= url('recover'); ?>/send/pass" method="post">
      <?php csrf_field(); ?>
      <fieldset>
        <label for="password">
          <?= __('app.new_password'); ?>
        </label>
        <input type="password" name="password" id="password">
      </fieldset>
      <p>
        <input type="hidden" name="code" id="code" value="<?= $data['code']; ?>">
        <input type="hidden" name="user_id" id="user_id" value="<?= $data['user_id']; ?>">
        <?= Html::sumbit(__('app.reset')); ?>
        <?php if (config('general.invite') == false) : ?>
          <span class="mr5 ml5 text-sm"><a href="<?= url('register'); ?>"><?= __('app.registration'); ?></a></span>
        <?php endif; ?>
        <span class="mr5 ml5 text-sm"><a href="<?= url('login'); ?>"><?= __('app.sign_in'); ?></a></span>
      </p>
    </form>
  </div>
</main>