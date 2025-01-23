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
    <form id="s-page" method="post" action="<?= url('search.go', method: 'post'); ?>">
      <input class="search w-100 bg-white" type="text" name="q" value="<?= htmlEncode($q); ?>" placeholder="<?= __('search.find'); ?>" class="search">
      <input name="cat" value="<?= $uri; ?>" type="hidden">
      <?= $container->csrf()->field(); ?>
      <div>
        <button class="btn btn-small<?php if ($uri == 'post') : ?> btn-primary<?php else : ?> btn-outline-primary<?php endif; ?>" name="cat" value="post" form="s-page"><?= __('search.posts'); ?></button>
        <button class="btn btn-small<?php if ($uri == 'comment') : ?> btn-primary<?php else : ?> btn-outline-primary<?php endif; ?>" name="cat" value="comment" form="s-page"><?= __('search.comments'); ?></button>
        <button class="btn btn-small<?php if ($uri == 'website') : ?> btn-primary<?php else : ?> btn-outline-primary<?php endif; ?>" name="cat" value="website" form="s-page"><?= __('search.websites'); ?></button>
      </div>
    </form>
  </div>