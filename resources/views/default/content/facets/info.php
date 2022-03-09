<?php $topic = $data['facet']; ?>
<main class="col-span-7 mb-col-12">
  <?= Tpl::import('/content/facets/topic-header', ['topic' => $topic, 'user' => $user, 'data' => $data]); ?>
  <div class="box-white">
    <h2 class="mt5 mb5"><?= Translate::get('Wiki'); ?></h2>
    <?= $topic['facet_info']; ?>
  </div>

  <?= Tpl::import('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>

</main>
<aside class="col-span-3 mb-none">
  <div class="box-white gray-400">
    <i class="bi bi-calendar-week mr5 middle"></i>
    <span class="middle"><?= $topic['facet_add_date']; ?></span>
  </div>
  <?= Tpl::import('/_block/sidebar/topic', ['data' => $data]); ?>
</aside>