<?php header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); ?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Error 404</title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body class="max-width mr-auto w-100 grid grid-cols-12 justify-between">
  <div class="col-span-2 mb-none"></div>
  <div class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray hidden p20 mt15">
    <div class="mb0">
      <h1 class="text-3xl font-semibold gray mt0 mb0">404</h1>
      <p class="gray-600"><?= Translate::get('the page does not exist'); ?></p>
      <a class="btn btn-primary" href="/">
        <?= Translate::get('to main'); ?>
      </a>
      <div class="mt15 text-sm gray-400">
        <?= Translate::get('the page has been removed'); ?>...
      </div>
    </div>
    <img class="right" src="/assets/images/agouti_footer.gif">
  </div>

</body>

</html>