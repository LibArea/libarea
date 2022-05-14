<?php $topic = $data['facet']; ?>
<main class="w-100">
  <?php if ($topic['facet_is_deleted'] == 0) : ?>
    <?= insert('/content/facets/topic-header', ['topic' => $topic, 'data' => $data]); ?>
    <?= insert('/content/post/post', ['data' => $data]); ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('topic', ['slug' => $topic['facet_slug']])); ?>

  <?php else : ?>
    <div class="center">
      <i class="bi-x-octagon text-8xl"></i>
      <div class="mt5 gray"><?= __('app.remote'); ?></div>
    </div>
  <?php endif; ?>
</main>
