<?php $topic = $data['facet']; ?>
<main>
  <?php if ($topic['facet_is_deleted'] == 0) : ?>
    <?= insert('/content/facets/topic-header', ['topic' => $topic, 'data' => $data]); ?>
    <?= insert('/content/post/post', ['data' => $data]); ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('topic', ['slug' => $topic['facet_slug']])); ?>

  <?php else : ?>
    <div class="box center gray-600">
      <svg class="icons icon-max">
        <use xlink:href="/assets/svg/icons.svg#x-octagon"></use>
      </svg>
      <div class="mt5 gray"><?= __('app.remote'); ?></div>
    </div>
  <?php endif; ?>
</main>
<aside>
  <?php if ($topic['facet_is_deleted'] == 0) : ?>
    <div class="box-flex bg-beige justify-between">
      <div class="center">
        <div class="uppercase text-sm gray-600"><?= __('app.posts'); ?></div>
        <?= $topic['facet_count']; ?>
      </div>
      <div class="center relative">
        <div class="uppercase text-sm gray-600"><?= __('app.reads'); ?></div>
        <div data-id="<?= $topic['facet_id']; ?>" data-slug="<?= $topic['facet_slug']; ?>" class="focus-user trigger sky">
          <?= $topic['facet_focus_count']; ?>
        </div>
        <div class="list_<?= $topic['facet_id']; ?> dropdown"></div>
      </div>
    </div>

    <?php if (!empty($data['pages'])) : ?>
      <div class="sticky top-sm">
        <div class="box bg-lightgray text-sm">
          <h3 class="uppercase-box"><?= __('app.pages'); ?></h3>
          <?php foreach ($data['pages'] as $ind => $row) : ?>
            <a class="flex mb10 items-center gray-600" href="">
              <?= $row['post_title']; ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?= insert('/_block/sidebar/topic', ['data' => $data]); ?>
    <?php if (!empty($data['writers'])) : ?>
      <div class="sticky top-sm">
        <div class="box bg-lightgray text-sm">
          <h3 class="uppercase-box"><?= __('app.writers'); ?></h3>
          <ul>
            <?php foreach ($data['writers'] as $ind => $row) : ?>
              <li class="mb10">
                <a class="gray-600" href="<?= url('profile', ['login' => $row['login']]); ?>">
                  <?= Html::image($row['avatar'], $row['login'], 'img-sm', 'avatar', 'max'); ?>
                  <?= $row['login']; ?> (<?= $row['hits_count']; ?>)
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

  <?php endif; ?>
</aside>