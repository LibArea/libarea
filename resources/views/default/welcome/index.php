<main class="col-span-9">
  <?= breadcrumb('/', Translate::get('home'), null, null, Translate::get('welcome')); ?>
  <?= Translate::get('hi'); ?>, <?= $uid['user_login']; ?>!
  <div class="mt10 max-w780">
    <?= Translate::get('welcome-info'); ?>
  </div>
</main>