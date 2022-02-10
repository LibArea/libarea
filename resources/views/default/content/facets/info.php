<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>
  </nav>
</div>

<?php $facet = $data['facet']; ?>
<main class="col-span-7 mb-col-12">
  <div class="box-white">
    <a class="text-sm" title="<?= Translate::get('topics-all'); ?>" href="/topics">
      ← <?= Translate::get('topics'); ?>
    </a>
    <h1 class="mb0 mt10 text-2xl">
      <a href="<?= getUrlByName('topic', ['slug' => $facet['facet_slug']]); ?>">
        <?= $facet['facet_seo_title']; ?>
      </a>
      <?php if (UserData::checkAdmin()) { ?>
        <a class="right gray-600" href="<?= getUrlByName('facet.edit', ['id' => $facet['facet_id']]); ?>">
          <i class="bi bi-pencil"></i>
        </a>
      <?php } ?>
    </h1>
    <h3 class="mt5 mb5"><?= Translate::get('info'); ?></h3>
    <?= $facet['facet_info']; ?>
  </div>

  <?= Tpl::import(
    '/_block/related-posts',
    [
      'related_posts'   => $data['related_posts'],
      'number'          => 'yes'
    ]
  ); ?>

</main>
<aside class="col-span-3 relative mb-none">
  <div class="box-white">
    <center>
      <a title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $facet['facet_slug']]); ?>">
        <?= facet_logo_img($facet['facet_img'], 'max', $facet['facet_title'], 'topic-img'); ?>
      </a>
    </center>
    <hr>
    <div class="gray-600">
      <i class="bi bi-calendar-week mr5 middle"></i>
      <span class="middle"><?= $facet['facet_add_date']; ?></span>
    </div>
  </div>

  <?= Tpl::import('/_block/sidebar/topic', ['data' => $data]); ?>
</aside>