<?php $topic = $data['facet']; ?>
<main class="col-two">
  <?= Tpl::insert('/content/facets/topic-header', ['topic' => $topic, 'data' => $data]); ?>
  <div class="box">
    <h2 class="mt5 mb5"><?= __('app.wiki'); ?></h2>
    <?= Content::text($topic['facet_info'], 'text'); ?>
  </div>
  <div class="box">
    <?= Tpl::insert('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>
  </div>
</main>
<aside>
  <div class="box gray-600 bg-violet">
    <i class="bi-calendar-week mr5 middle"></i>
    <span class="middle"><?= Html::langDate($topic['facet_add_date']); ?></span>
  </div>
  <?= Tpl::insert('/_block/sidebar/topic', ['data' => $data]); ?>
</aside>