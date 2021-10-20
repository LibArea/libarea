<?php header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); ?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Error 404</title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body class="bg-gray-000 pt0">
  <div class="wrap max-w-460 items-center pt60 pr10 pl10">
    <div class="left mr20">
      <h1 class="size-31 font-semibold gray mb0">404</h1>
      <p class="gray-light"><?= lang('the page does not exist'); ?></p>
      <a class="button br-rd5 white" href="/"><?= lang('to main'); ?></a>
      <div class="mt15 size-14 gray-light-2">
        <?= lang('the page has been removed'); ?>...
      </div>
    </div>
    <div class="left ml20 right">
      <img class="mt60" src="/assets/images/agouti_footer.gif">
    </div>
  </div>
</body>

</html>