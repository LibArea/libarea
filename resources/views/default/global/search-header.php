<?php
$uri = $data['type'] ?? 'post';
$q = $data['q'];
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body <?php if ($container->cookies()->get('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>

  <header class="d-header">
    <div class="wrap">
      <div class="d-header_contents">

        <div class="flex flex-auto">
          <div class="box-logo">
            <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta', 'name'); ?></a>
          </div>

          <div class="box-search">
            <form method="get" action="<?= url('search.go'); ?>">
              <input type="text" name="q" value="<?= $q; ?>" placeholder="<?= __('search.find'); ?>" class="search">
              <input name="cat" value="<?= $uri; ?>" type="hidden">
              <?= $container->csrf()->field(); ?>
            </form>
            <div class="box-results none" id="search_items"></div>
          </div>
        </div>

		<?= insert('/_block/navigation/user-bar-header', ['facet_id' => $facet['facet_id'] ?? false]); ?> 
      </div>
    </div>
  </header>

  <div id="contentWrapper" class="wrap mb20">
    <ul class="nav inline ml10">
      <li<?php if ($uri == 'post') : ?> class="active" <?php endif; ?>>
        <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=post"><?= __('search.posts'); ?></a>
      </li>
      <li<?php if ($uri == 'comment') : ?> class="active" <?php endif; ?>>
        <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=comment"><?= __('search.comments'); ?></a>
      </li>
      <li<?php if ($uri == 'website') : ?> class="active" <?php endif; ?>>
        <a href="<?= url('search.go'); ?>?q=<?= $q; ?>&cat=website"><?= __('search.websites'); ?></a>
      </li>
    </ul>
  </div>