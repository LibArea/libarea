<?= insert('/meta', ['meta' => $meta]); ?>

<body class="general<?php if ($container->cookies()->get('dayNight')->value() == 'dark') : ?> dark<?php endif; ?>">

  <div class="box-center">
    <form action="<?= url('search.go'); ?>">
      <input class="search-input br5" placeholder="<?= __('search.name'); ?>..." name="q">
      <button class="search-button-icon br5 pointer"><svg class="icon">
          <use xlink:href="/assets/svg/icons.svg#search"></use>
        </svg></button>
      <?= $container->csrf()->field(); ?>
    </form>
    <div class="center">
      <div class="text-sm gray-600"><?= __('search.help'); ?></div>
      <a class="text-sm" title="<?= __('search.on_website'); ?>" href="/"><?= config('meta', 'name'); ?></a>
    </div>
  </div>

</body>

</html>