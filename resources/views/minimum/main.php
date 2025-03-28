<?php
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/global/header', ['meta' => $meta]); ?>

<body class="body-minimum<?php if ($container->cookies()->get('dayNight') == 'dark') : ?> dark<?php endif; ?>">
  <header class="text-header wrap">
    <div class="d-header_contents justify-between">

      <div class="flex gap-lg items-center">
        <a title="<?= __('app.home'); ?>" class="logo" href="/">
          L
        </a>
        <ul class="nav scroll-menu mb-w150">
          <?= insert('/_block/navigation/config/home-nav'); ?>
        </ul>
      </div>
      <?= insert('/_block/navigation/user-bar-header', ['facet_id' => $facet['facet_id'] ?? false]); ?>
    </div>
  </header>

  <div id="contentWrapper">

    <?= $content; ?>

  </div>

  </div>
  <footer class="footer">
    <div class="wrap">
      <div class="text-sm right">
        <a class="gray-600 mr15" href="<?= url('search'); ?>"><?= __('app.search'); ?></a>
        <a class="gray-600 mr15" href="<?= url('blogs.all'); ?>"><?= __('app.blogs'); ?></a>
        <a class="gray-600 mr15" href="<?= url('topics.all'); ?>"><?= __('app.topics'); ?></a>
        <a class="gray-600" href="/info/article/information"><?= __('app.information'); ?></a>
      </div>
    </div>
  </footer>


  <?= insert('/global/footer'); ?>