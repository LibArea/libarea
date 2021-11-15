<?php header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); ?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Error 404</title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body class="max-width mr-auto w-100 grid grid-cols-12 gap-4 pr10 pl10 justify-between">
  <div class="col-span-2 no-mob"></div>
  <div class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray pt10 pr15 pb5 pl15">
    <div class="mb20">
      <h1 class="size-31 font-semibold gray mb0">404</h1>
      <p class="gray-light"><?= Translate::get('the page does not exist'); ?></p>
      <a class="bg-blue-800 br-box-blue bg-hover-light-blue pt5 pr15 pb5 pl15 br-rd5 white white-hover" href="/">
        <?= Translate::get('to main'); ?>
      </a>
      <div class="mt15 size-14 gray-light-2">
        <?= Translate::get('the page has been removed'); ?>...
      </div>
    </div>
    <img class="right" src="/assets/images/agouti_footer.gif">
  </div>
   
</body>

</html>