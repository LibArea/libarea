<?php $blog = $data['facet'];
if ($blog['facet_is_deleted'] == 0) : ?>

  <div class="w-100">
    <?= insert('/content/facets/blog-header', ['data' => $data]); ?>
    <main>
      <?= insert('/content/post/post-card', ['data' => $data]); ?>
      <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('blog', ['slug' => $blog['facet_slug']])); ?>
    </main>
  </div>
<?php else : ?>
  <main>
    <div class="box center gray-600">
      <svg class="icon max">
        <use xlink:href="/assets/svg/icons.svg#x-octagon"></use>
      </svg>
      <div class="mt5 gray"><?= __('app.remote'); ?></div>
    </div>
  </main>
<?php endif; ?>