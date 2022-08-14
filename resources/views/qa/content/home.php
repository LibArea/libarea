<main>

  <ul class="nav scroll">
    <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.home')]); ?>
  </ul>

  <?= insert('/content/post/post-card', ['data' => $data]); ?>

  <?php if (UserData::getUserScroll()) : ?>
    <div id="scrollArea"></div>
    <div id="scroll"></div>
  <?php else : ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  <?php endif; ?>
</main>

<aside>
  <?php if (!UserData::checkActiveUser()) : ?>
    <?= insert('/_block/login'); ?>
  <?php endif; ?>

  <?= insert('/_block/facet/my-facets', ['topics_user' => $data['topics_user']]); ?>

  <?php if (is_array($data['topics'])) : ?>
    <?php if (count($data['topics']) > 0) : ?>
      <div class="box bg-lightgray">
        <h4 class="uppercase-box"><?= __('app.recommended'); ?></h4>
        <ul>
          <?php foreach ($data['topics'] as $key => $recomm) : ?>
            <li class="mb20">
              <a href="<?= url('topic', ['slug' => $recomm['facet_slug']]); ?>">
                <?= Img::image($recomm['facet_img'], $recomm['facet_title'], 'img-base mr5', 'logo', 'max'); ?>
                <?= $recomm['facet_title']; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <div class="sticky top-sm">
    <?= insert('/_block/latest-answers-tabs', ['latest_answers' => $data['latest_answers']]); ?>
  </div>
</aside>