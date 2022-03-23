<?php $topic = $data['facet']; ?>
<main>
  <?= Tpl::import('/content/facets/topic-header', ['topic' => $topic, 'user' => $user, 'data' => $data]); ?>
  <div class="box-white">
    <?php if (!empty($data['writers'])) { ?>
      <div class="flex items-center mb20 mt10">
        <i class="bi-award red text-3xl mr10"></i>
        <h2 class="gray-600"><?= Translate::get('by.deposit'); ?></h2>
      </div>
      <hr class="mb20">
      <?php foreach ($data['writers'] as $ind => $row) { ?>
        <div class="flex flex-auto items-center mb20">
          <div class="w94">
            <?= $row['hits_count']; ?>
          </div>
          <div class="flex">
            <a class="flex items-center hidden gray-600" href="<?= getUrlByName('profile', ['login' => $row['login']]); ?>">
              <?= Html::image($row['avatar'], $row['login'], 'ava-base', 'avatar', 'max'); ?>
              <div class="ml5">
                <div class="gray-600"><?= $row['login']; ?></div>
                <?php if ($row['about']) { ?> <div class="gray-600 mb-none text-sm"><?= Html::cutWords($row['about'], 5); ?></div><?php } ?>
              </div>
            </a>
          </div>
        </div>
      <?php } ?>
    <?php } ?>
  </div>
</main>
<aside>
  <div class="box-white gray-600">
    <i class="bi-calendar-week mr5 middle"></i>
    <span class="middle"><?= Html::langDate($topic['facet_add_date']); ?></span>
  </div>
  <?= Tpl::import('/_block/sidebar/topic', ['data' => $data]); ?>
</aside>