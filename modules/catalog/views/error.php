<?php
/**
 * HTML template for the HTTP GET method error page.
 *
 * HTML-шаблон для страницы ошибок HTTP метода GET.
 *
 * @var $httpCode int
 * @var $message string
 * @var $apiVersion int
 * @var $uriPrefix string
 */
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width" />
    <meta name="robots" content="noindex, noarchive" />
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
    <title><?= $httpCode . '. ' . $message ?></title>
</head>
<body class="body-bg-fon mt30">

  <main class="max-w-md mr-auto box">
    <h1 class="text-3xl gray"><?= $httpCode ?></h1>
    <p class="gray-600">
      <?= $message ?>
    </p>
    <a class="btn btn-primary" href="<?= url('web'); ?>"><?= __('404.to_main'); ?></a>
  </main>

</body>

</body>
</html>
