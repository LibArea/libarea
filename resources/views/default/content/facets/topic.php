<?php
$topic = $data['facet'];
?>
<main>
  <?php if ($topic['facet_is_deleted'] == 0) : ?>
    <?= insert('/content/facets/topic-header', ['topic' => $topic, 'data' => $data]); ?>

    <?= insert('/content/post/type-post', ['data' => $data]); ?>

    <?php
    $sort = $container->request()->get('sort')->value();
    $sort = $sort ? '&sort=' . $sort : '';
    ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('topic', ['slug' => $topic['facet_slug']]), '?', $sort); ?>

  <?php else : ?>
    <div class="box center gray-600">
      <svg class="icon max">
        <use xlink:href="/assets/svg/icons.svg#x-octagon"></use>
      </svg>
      <div class="mt5 gray"><?= __('app.topic_deleted'); ?></div>
    </div>
  <?php endif; ?>
</main>
<aside>
  <?php if ($topic['facet_is_deleted'] == 0) : ?>

    <?= insert('/_block/facet/topic', ['data' => $data]); ?>

    <?php if (!empty($data['writers'])) : ?>
      <div class="sticky top-sm">
        <div class="box text-sm">
          <h4 class="uppercase-box"><?= __('app.writers'); ?></h4>
          <ul>
            <?php foreach ($data['writers'] as $ind => $row) : ?>
              <li class="mb10">
                <a class="gray-600" href="<?= url('profile', ['login' => $row['login']]); ?>">
                  <?= Img::avatar($row['avatar'], $row['login'], 'img-sm mr5', 'max'); ?>
                  <span class="nickname<?php if (Html::loginColor($row['created_at'] ?? false)) : ?> new<?php endif; ?>">
                    <?= $row['login']; ?>
                  </span>
                  (<?= Html::formatToHuman($row['hits_count']); ?>)
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

  <?php endif; ?>
</aside>