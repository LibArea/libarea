<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?= lang('site under reconstruction '); ?></title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body class="bg-gray-000 pb20 mb20">
  <div class="wrap max-w-460 items-center mt20 pt20 mb20 pb20">
    <div class="left mr20">
      <h1 class="size-31 font-semibold gray mb0">Opss</h1>
      <p class="gray-light"><?= lang('site under reconstruction'); ?>...</p>
    </div>
    <div class="left ml20 mt20 right">
      <img class="mt15" src="/assets/images/agouti_footer.gif">
    </div>
  </div>
  <div class="block mt20 pt20 mb20 pb20"></div>
  <div class="wrap max-w-460 items-center mt20 pt20 mb20 pb20">
      <form class="" action="/login" method="post">
        <?php csrf_field(); ?>
        <div class="boxline">
          <label for="email" class="form-label">E-mail</label>
          <input type="text" class="form-input" placeholder="<?= lang('enter'); ?>  e-mail" name="email" id="email">
        </div>
        <div class="boxline">
          <label for="password" class="form-label"><?= lang('password'); ?></label>
          <input type="password" placeholder="<?= lang('enter your password'); ?>" name="password" id="password" class="form-input">
        </div>
        <div class="boxline">
          <button type="submit" class="button-primary pt10 pr15 pb10 pl15 size-13 white">
            <?= lang('sign in'); ?>
          </button>
        </div>
      </form>
  </div>
</body>

</html>