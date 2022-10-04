<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?09'); ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body>

  <div class="box-center">
    <form action="<?= url('search.go'); ?>">
      <input class="search-input br5" placeholder="<?= __('search.name'); ?>..." name="q">
      <button class="search-button-icon br5 pointer"><svg class="icons">
          <use xlink:href="/assets/svg/icons.svg#search"></use>
        </svg></button>
      <?= csrf_field() ?>
    </form>
    <div class="center">
      <div class="text-sm gray-600"><?= __('search.help'); ?></div>
      <a class="text-sm" title="<?= __('search.to_main'); ?>" href="/"><?= config('meta.name'); ?></a>
    </div>
  </div>

</body>

</html>