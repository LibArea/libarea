<?php header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); ?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Error 404</title>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body class="body-bg-fon mt30">

  <main class="max-w780 mr-auto box-white">
    <h1 class="text-3xl font-semibold gray mt0 mb0">404</h1>
    <p class="gray-600"><?= Translate::get('page.not'); ?></p>
    <a class="btn btn-primary" href="/"><?= Translate::get('to.main'); ?></a>
    <p class="gray-600"><?= Translate::get('page.removed'); ?>...</p>
  </main>

</body>

</html>