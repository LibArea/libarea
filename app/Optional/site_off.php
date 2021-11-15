<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?= Translate::get('site under reconstruction'); ?></title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body class="bg-gray-000 pb20 mb20">
  <div class="max-width mr-auto w-100 max-w-460 items-center mt20 pt20 mb20 pb20">
    <div class="left mr20">
      <h1 class="size-31 font-semibold gray mb0">Opss</h1>
      <p class="gray-light"><?= Translate::get('site under reconstruction'); ?>...</p>
    </div>
    <div class="left ml20 mt20 right">
      <img class="mt15" src="/assets/images/agouti_footer.gif">
    </div>
  </div>
  <div class="block mt20 pt20 mb20 pb20"></div>
  <div class="max-width mr-auto w-100 max-w-460 items-center mt20 pt20 mb20 pb20">
    <form class="" action="/login" method="post">
      <?php csrf_field(); ?>
      <div class="mb20">
        <label for="email" class="block">E-mail</label>
        <input type="text" class="w-100 h30" placeholder="<?= Translate::get('enter'); ?>  e-mail" name="email" id="email">
      </div>
      <div class="mb20">
        <label for="password" class="form-label"><?= Translate::get('password'); ?></label>
        <input type="password" placeholder="<?= Translate::get('enter your password'); ?>" name="password" id="password" class="w-100 h30">
      </div>
      <div class="mb20">
        <?= sumbit(Translate::get('sign in')); ?>
      </div>
    </form>
  </div>
</body>

</html>