<?php
$type = $data['type'] ?? false;
$post = $data['post'] ?? false;
$facet = $data['facet'] ?? false
?>

<?= insert('/global/header', ['meta' => $meta, 'type' => $type]); ?>

<body class="<?= $type; ?><?= modeDayNight(); ?>">
  <header class="d-header<?php if ($post || $facet) : ?> scroll-hide-search<?php endif; ?>">
        <div class="flex flex-auto">
          <div class="box-logo">
            <svg class="icon large menu__button none">
              <use xlink:href="/assets/svg/icons.svg#menu"></use>
            </svg>
            <a title="<?= __('app.home'); ?>" class="logo" href="/"><?= config('meta', 'name'); ?></a>
          </div>

          <?php if ($post) : ?>
            <div class="d-header-post none">
              <span class="v-line mb-none"></span>
              <a class="mb-none" href="<?= post_slug($post['post_type'], $post['post_id'], $post['post_slug']) ?>">
                <?= $data['post']['post_title'] ?>
              </a>
            </div>
          <?php endif; ?>

          <?php
          $facet_type = $facet['facet_type'] ?? false;
          $facetIcon = $facet_type == 'topic' ? $facet : false;
          if ($facetIcon) : ?>
            <div class="d-header-facet none">
              <span class="v-line mb-none"></span>
              <a class="mb-none" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]) ?>">
                <?= Img::image($facet['facet_img'], $facet['facet_title'], 'img-base mr15', 'logo', 'max'); ?>
                <?= $facet['facet_title']; ?>
              </a>
              <span class="gray-600 text-sm lowercase mb-none"> - <?= $facet['facet_short_description']; ?></span>
            </div>
          <?php endif; ?>

          <div class="box-search relative">
            <form class="form" method="get" action="<?= url('search.go'); ?>">
              <input data-id="topic" type="text" name="q" autocomplete="off" id="find" placeholder="<?= __('app.find'); ?>" class="search">
            </form>
            <div class="box-results none" id="search_items"></div>
          </div>
        </div>

        <?= insert('/_block/navigation/user-bar-header', ['facet_id' => $facet['facet_id'] ?? false]); ?>
  </header>

  <div id="contentWrapper">
      <nav class="menu__left mb-none">
        <ul class="menu">
          <?= insert('/_block/navigation/config/left-menu', ['type' => $type, 'topics_user' => $data['topics_user']]); ?>
        </ul>
        <footer>
          <b class="uppercase-box ml10"><?= config('meta', 'name'); ?></b>
          <ul class="menu">
            <?= insert('/_block/navigation/nav-footer'); ?>
          </ul>
          <div class="ml10 mb5 text-sm gray-600">
            <?= config('meta', 'name'); ?> &copy; <?= date('Y'); ?>
          </div>
        </footer>
      </nav>
    <?= $content; ?>
  </div>

  <?= insert('/global/footer'); ?>