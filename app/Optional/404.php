<?php header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); ?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?= __('404.error_404'); ?></title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body class="body-bg-fon mt30">

  <main class="max-w780 mr-auto box">
    <h1 class="text-3xl gray">404</h1>
    <p class="gray-600">
      <?= __('404.page_not'); ?> <br />
      <?= __('404.page_removed'); ?>
    </p>
    <a class="btn btn-primary" href="/"><?= __('404.to_main'); ?></a>
  </main>

</body>

</html>