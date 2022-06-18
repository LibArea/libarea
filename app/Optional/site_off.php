<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?= __('off.under_reconstruction'); ?></title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body class="body-bg-fon mt20">

  <main class="box mr-auto max-w780">
    <h1 class="text-3xl gray">Opss</h1>
    <p class="gray-600"><?= __('off.under_reconstruction'); ?>...</p>

    <form class="max-w300" action="/login" method="post">
      <?php csrf_field(); ?>
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
  <div class="center gray-600 mt5"><?= config('meta.name'); ?></a>

</body>

</html>