<?php $blog = $data['facet'];
if ($blog['facet_is_deleted'] == 0) : ?>

  <div class="w-100">
    <?= insert('/content/facets/blog-header', ['data' => $data]); ?>
    <main>
      <?= insert('/content/publications/post-card', ['data' => $data]); ?>
      <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('blog', ['slug' => $blog['facet_slug']])); ?>
    </main>
  </div>
<?php else : ?>
  <main>
    <div class="box center gray-600">
      <?= icon('icons', 'x-octagon', 164); ?>
      <div class="mt5 gray"><?= __('app.remote'); ?></div>
    </div>
  </main>
<?php endif; ?>