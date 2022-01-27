<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?= Translate::get('site under reconstruction'); ?></title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body class="bg-gray-000">
  <div class="max-width mr-auto w-100 grid grid-cols-12 p20 justify-between">
    <div class="col-span-2 mb-none"></div>
    <main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray p15">
      <h1 class="text-3xl font-normal gray m0">Opss</h1>
      <p class="gray-600"><?= Translate::get('site under reconstruction'); ?>...</p>

      <form class="mb20" action="/login" method="post">
        <?php csrf_field(); ?>
        <div class="mb20 max-w640">
          <label class="block mb5">E-mail</label>
          <input type="text" class="w-100 h30 pl5" placeholder="<?= Translate::get('enter'); ?>  e-mail" name="email">
          <label class="block mt20 mb5"><?= Translate::get('password'); ?></label>
          <input type="password" placeholder="<?= Translate::get('enter your password'); ?>" name="password" class="w-100 h30 pl5">
        </div>
        <img class="mb5 right" src="<?= Config::get('meta.img_footer_path'); ?>">
        <?= sumbit(Translate::get('sign.in')); ?>
      </form>

    </main>
    <div class="col-span-2 mb-none"></div>
</body>

</html>