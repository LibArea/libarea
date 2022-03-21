<?php $topic = $data['facet']; ?>
<main class="col-two">
  <?= Tpl::import('/content/facets/topic-header', ['topic' => $topic, 'user' => $user, 'data' => $data]); ?>
  <div class="box-white">
    <h2 class="mt5 mb5"><?= Translate::get('Wiki'); ?></h2>
    <?= $topic['facet_info']; ?>
  </div>
  <div class="box-white">
    <?= Tpl::import('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>
  </div>
</main>
<aside>
  <div class="box-white gray-600 bg-violet-50">
    <i class="bi-calendar-week mr5 middle"></i>
    <span class="middle"><?= $topic['facet_add_date']; ?></span>
  </div>
  <?= Tpl::import('/_block/sidebar/topic', ['data' => $data]); ?>
</aside>