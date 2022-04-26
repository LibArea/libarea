<main class="max-w780 mr-auto box">
  <h1 class="center"><?= __($data['sheet']); ?></h1>
  <form class="form max-w300" action="<?= url('recover.send'); ?>" method="post">
    <?php csrf_field(); ?>

    <fieldset>
      <label for="post_title"><?= __('E-mail'); ?></label>
      <input type="email" required="" name="email">
    </fieldset>

    <?= Tpl::insert('/_block/captcha'); ?>

    <fieldset>
      <?= Html::sumbit(__('reset')); ?>
      <?php if (config('general.invite') == false) : ?>
        <span class="mr5 ml15 text-sm"><a href="<?= url('register'); ?>"><?= __('registration'); ?></a></span>
      <?php endif; ?>
      <span class="mr5 ml15 text-sm"><a href="<?= url('login'); ?>"><?= __('sign.in'); ?></a></span>
    </fieldset>
  </form>
  <p><?= __('login.use.condition'); ?>.</p>
  <p><?= __('recover.info'); ?></p>
</main>