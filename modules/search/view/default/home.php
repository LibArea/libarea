<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?= $meta; ?>
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
  <link rel="icon" href="/favicon.ico" type="image/png">
</head>

<body>

  <div class="box-center">
    <form action="<?= getUrlByName('search.go'); ?>">
      <input class="search-input br5" placeholder="<?= __('search'); ?>..." name="q">
      <button class="search-button-icon br5"><i class="bi-search"></i></button>
      <?= csrf_field() ?>
    </form>
    <div class="center">
      <div class="text-sm gray-600"><?= __('search.help'); ?></div>
      <a class="text-sm" title="<?= __('to.main'); ?>" href="/"><?= Config::get('meta.name'); ?></a>
    </div>
  </div>

</body>

</html>