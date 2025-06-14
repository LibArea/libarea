<?php
$uri = $data['type'] ?? 'content';
$q = $data['q'];
?>

<?= insert('global/header', ['meta' => $meta, 'type' => 'search']); ?>

<body class="search-page<?php if ($container->cookies()->get('dayNight')->value() == 'dark') : ?> dark<?php endif; ?>">

  <header class="d-header justify-between">
     
        <div class="box-logo">
          <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta', 'name'); ?></a>
        </div>
        <?= insert('/_block/navigation/user-bar-header', ['dontShowSearchButton' => true]); ?>
  </header>
 <main>
  <div class="mb20">
    <form id="s-page" method="get" action="<?= url('search.go'); ?>">
      <input class="search w-100" type="text" name="q" value="<?= htmlEncode($q); ?>" placeholder="<?= __('search.find'); ?>" class="search">
      <input name="cat" value="<?= $uri; ?>" type="hidden">
    </form>
  </div>

    <ul class="nav inline ml10">
      <li<?php if ($uri == 'content') : ?> class="active" <?php endif; ?>>
        <a href="<?= url('search.go'); ?>?q=<?= htmlEncode($q); ?>&cat=content"><?= __('app.content'); ?></a>
        </li>
        <!--li<?php if ($uri == 'comment') : ?> class="active" <?php endif; ?>>
          <a href="<?= url('search.go'); ?>?q=<?= htmlEncode($q); ?>&cat=comment"><?= __('search.comments'); ?></a>
          </li-->
    </ul>
   </main>