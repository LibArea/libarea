<main class="box w-100">
  <h1><?= __('app.password_recovery'); ?></h1>
  <form class="max-w300" action="<?= url('new.pass'); ?>" method="post">
    <?php csrf_field(); ?>
    <fieldset>
      <label for="password"><?= __('app.new_password'); ?></label>
      <input id="password" type="password" name="password">
      <span class="showPassword absolute gray-600 right5 mt5"><i class="bi-eye"></i></span>
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
</main>