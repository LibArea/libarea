<div class="sticky top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.left'),
      ); ?>
</div>
<?php $facet = $data['facet']; ?>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd5 br-box-gray pt5 pr15 pb5 mb15 pl15">
    <a class="size-14" title="<?= Translate::get('topics-all'); ?>" href="/topics">
      ← <?= Translate::get('topics'); ?>
    </a>
    <h1 class="mb0 mt10 size-24">
      <a href="<?= getUrlByName('topic', ['slug' => $facet['facet_slug']]); ?>">
        <?= $facet['facet_seo_title']; ?>
      </a>
      <?php if ($uid['user_trust_level'] == 5) { ?>
        <a class="right gray-light" href="<?= getUrlByName('topic.edit', ['id' => $facet['facet_id']]); ?>">
          <i class="bi bi-pencil size-15"></i>
        </a>
      <?php } ?>
    </h1>
    <h3 class="mt5 mb5"><?= Translate::get('info'); ?></h3>
    <?= $facet['facet_info']; ?>
  </div>

  <?= import(
    '/_block/related-posts',
    [
      'related_posts' => $data['related_posts'],
      'number' => 'yes'
    ]
  ); ?>

</main>
<aside class="col-span-3 relative no-mob">
  <div class="bg-white br-rd5 br-box-gray p15 mb15 size-15">
    <center>
      <a title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $facet['facet_slug']]); ?>">
        <?= facet_logo_img($facet['facet_img'], 'max', $facet['facet_title'], 'topic-img'); ?>
      </a>
    </center>
    <hr>
    <div class="gray-light">
      <i class="bi bi-calendar-week mr5 middle"></i>
      <span class="middle"><?= $facet['facet_add_date']; ?></span>
    </div>
  </div>

  <?= import('/_block/sidebar/topic', ['data' => $data, 'uid' => $uid]); ?>
</aside>
<?= import('/_block/wide-footer'); ?>