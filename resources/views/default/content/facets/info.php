<?php $topic = $data['facet']; ?>
<main>
  <?= Tpl::insert('/content/facets/topic-header', ['topic' => $topic, 'user' => $user, 'data' => $data]); ?>
  <div class="box-white">
    <h2 class="mt5 mb5"><?= Translate::get('Wiki'); ?></h2>
    <?= Content::text($topic['facet_info'], 'text'); ?>
  </div>
  <div class="box-white">
    <?= Tpl::insert('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>
  </div>
</main>
<aside>
  <div class="box-white gray-600">
    <i class="bi-calendar-week mr5 middle"></i>
    <span class="middle"><?= Html::langDate($topic['facet_add_date']); ?></span>
  </div>
  <?= Tpl::insert('/_block/sidebar/topic', ['data' => $data]); ?>
</aside>