<?php
$uri = $data['type'] ?? 'post';
$q = $data['q'];
?>

<?= insert('/meta', ['meta' => $meta, 'type' => 'search']); ?>

<body class="search-page<?php if ($container->cookies()->get('dayNight')->value() == 'dark') : ?> dark<?php endif; ?>">

  <header class="d-header">
    <div class="wrap">
      <div class="d-header_contents">
        <div class="box-logo">
          <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta', 'name'); ?></a>
        </div>
        <?= insert('/_block/navigation/user-bar-header', ['dontShowSearchButton' => true]); ?>
      </div>
    </div>
  </header>

  <div class="wrap mb20">
    <form id="s-page" method="get" action="<?= url('search.go'); ?>">
      <input class="search w-100 bg-white" type="text" name="q" value="<?= htmlEncode($q); ?>" placeholder="<?= __('search.find'); ?>" class="search">
      <input name="cat" value="<?= $uri; ?>" type="hidden">
    </form>
  </div>

  <div id="contentWrapper" class="wrap mb20">
    <ul class="nav inline ml10">
      <li<?php if ($uri == 'post') : ?> class="active" <?php endif; ?>>
        <a href="<?= url('search.go'); ?>?q=<?= htmlEncode($q); ?>&cat=post"><?= __('search.posts'); ?></a>
        </li>
        <li<?php if ($uri == 'comment') : ?> class="active" <?php endif; ?>>
          <a href="<?= url('search.go'); ?>?q=<?= htmlEncode($q); ?>&cat=comment"><?= __('search.comments'); ?></a>
          </li>
          <li<?php if ($uri == 'website') : ?> class="active" <?php endif; ?>>
            <a href="<?= url('search.go'); ?>?q=<?= htmlEncode($q); ?>&cat=website"><?= __('search.websites'); ?></a>
            </li>
    </ul>
  </div>