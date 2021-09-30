<main class="col-span-9">
  <?= breadcrumb('/', lang('home'), null, null, lang('welcome')); ?>

  <?= lang('hi'); ?>, <?= $uid['user_login']; ?>!
  <div class="mt10 max-width">
    <?= lang('welcome-info'); ?>
  </div>
</main>