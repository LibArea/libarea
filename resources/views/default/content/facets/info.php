<?php $topic = $data['facet']; ?>
<main>
  <?= insert('/content/facets/topic-header', ['topic' => $topic, 'data' => $data]); ?>
  <div class="box">
    <h2 class="m0"><?= __('app.wiki'); ?></h2>
    <?= markdown($topic['facet_info'] ?? '', 'text'); ?>
  </div>
  <div class="ml20">
    <?= insert('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>
  </div>
</main>
<aside>
  <div class="box gray-600 bg-beige">
    <svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#calendar"></use>
    </svg>
    <span class="middle"><?= langDate($topic['facet_date']); ?></span>
  </div>
  <?= insert('/_block/facet/topic', ['data' => $data]); ?>
</aside>