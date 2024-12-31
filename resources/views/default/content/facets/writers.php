<?php $topic = $data['facet']; ?>
<main>
  <?= insert('/content/facets/topic-header', ['topic' => $topic, 'data' => $data]); ?>
  <div class="box">
    <?php if (!empty($data['writers'])) : ?>
      <div class="flex gap items-center">
        <svg class="icon red">
          <use xlink:href="/assets/svg/icons.svg#award"></use>
        </svg>
        <h2 class="gray-600 m0"><?= __('app.by_deposit'); ?></h2>
      </div>
      <hr class="mb20">
      <?php foreach ($data['writers'] as $ind => $row) : ?>
        <div class="flex flex-auto items-center mb20">
          <div class="w60">
            <?= Html::formatToHuman($row['hits_count']); ?>
          </div>
          <div class="flex">
            <a class="flex items-center hidden gray-600" href="<?= url('profile', ['login' => $row['login']]); ?>">
              <?= Img::avatar($row['avatar'], $row['login'], 'img-base', 'max'); ?>
              <div class="ml10">
                <div class="gray-600<?php if (Html::loginColor($row['created_at'] ?? false)) : ?> new<?php endif; ?>">
                  <span class="nickname"><?= $row['login']; ?></span>
                </div>
                <?php if ($row['about']) : ?>
                  <div class="gray-600 mb-none text-sm">
                    <?= fragment($row['about'], 80); ?>
                  </div>
                <?php endif; ?>
              </div>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box gray-600">
    <svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#calendar"></use>
    </svg>
    <span class="middle"><?= langDate($topic['facet_date']); ?></span>
  </div>
  <?= insert('/_block/facet/topic', ['data' => $data]); ?>
</aside>