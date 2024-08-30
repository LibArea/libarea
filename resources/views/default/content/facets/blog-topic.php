<?php $blog = $data['facet'];
if ($blog['facet_is_deleted'] == 0) : ?>
  <main>
    <div class="mb20">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-lg gray-600">
            <a href="<?= url('blog', ['slug' => $blog['facet_slug']]); ?>"><?= $blog['facet_title']; ?></a> / <?= __('app.topic'); ?>
          </div>
          <h1 class="m0"><?= $data['topic']['facet_title']; ?></h1>
          <div class="text-sm gray-600">#<?= $data['topic']['facet_slug']; ?></div>
        </div>
        <div class="right">
          <a class="btn btn-outline-primary right" href="<?= url('topic', ['slug' => $data['topic']['facet_slug']]) ?>"><?= __('app.more_content'); ?></a>
          <div class="text-sm gray-600 clear"><?= __('app.read_more_posts'); ?> <?= config('meta', 'name'); ?></div>
        </div>
      </div>
    </div>

    <?= insert('/content/post/type-post', ['data' => $data]); ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('blog', ['slug' => $blog['facet_slug']])); ?>
  </main>
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