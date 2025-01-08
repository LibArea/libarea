<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?= __('off.under_reconstruction'); ?></title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body>

  <main class="box mr-auto max-w-md">
    <h1 class="text-3xl gray"><?= __('off.under_maintenance'); ?></h1>
    <p class="gray-600"><?= __('off.under_reconstruction'); ?>...</p>

    <form class="max-w-sm" action="/login" method="post">
      <?= $container->csrf()->field(); ?>
      <fieldset>
        <label for="email"><?= __('off.email'); ?></label>
        <input type="text" placeholder="<?= __('off.enter'); ?>  e-mail" name="email">
      </fieldset>
      <fieldset>
        <label for="password"><?= __('off.password'); ?></label>
        <input type="password" placeholder="<?= __('off.enter_password'); ?>" name="password">
      </fieldset>
      <?= Html::sumbit(__('off.sign_in')); ?>
    </form>

  </main>
  <div class="center gray-600 mt5 text-sm"><?= date('Y'); ?> © <?= config('meta', 'name'); ?></a>

</body>

</html>