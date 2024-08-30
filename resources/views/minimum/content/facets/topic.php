<?php $topic = $data['facet']; ?>
<main class="w-100">
  <?php if ($topic['facet_is_deleted'] == 0) : ?>
    <?= insert('/content/facets/topic-header', ['topic' => $topic, 'data' => $data]); ?>
    <?= insert('/content/post/post-card', ['data' => $data]); ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('topic', ['slug' => $topic['facet_slug']])); ?>

  <?php else : ?>
    <div class="box center gray-600">
      <svg class="icon max">
        <use xlink:href="/assets/svg/icons.svg#x-octagon"></use>
      </svg>
      <div class="mt5 gray"><?= __('app.remote'); ?></div>
    </div>
  <?php endif; ?>
</main>