<main>
  <div class="flex justify-between mb20">
    <ul class="nav scroll-menu">
      <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.home')]); ?>
    </ul>
    <div title="<?= __('app.post_appearance'); ?>" id="postmenu" class="m5">
      <svg class="icons pointer gray-600">
        <use xlink:href="/assets/svg/icons.svg#grid"></use>
      </svg>
    </div>
  </div>

  <?= insert('/content/post/type-post', ['data' => $data]); ?>

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
      <div class="box border-lightgray">
        <h4 class="uppercase-box"><?= __('app.recommended'); ?></h4>
        <ul>
          <?php foreach ($data['topics'] as $recomm) : ?>
            <li class="flex justify-between items-center mb10">
              <a class="flex items-center gap-min" href="<?= url('topic', ['slug' => $recomm['facet_slug']]); ?>">
                <?= Img::image($recomm['facet_img'], $recomm['facet_title'], 'img-base', 'logo', 'max'); ?>
                <?= $recomm['facet_title']; ?>
              </a>
              <?php if (UserData::getUserId()) : ?>
                <div data-id="<?= $recomm['facet_id']; ?>" data-type="facet" class="focus-id right inline text-sm red center">
                  <?= __('app.read'); ?>
                </div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <?php if (is_array($data['items'])) : ?>
    <div class="box border-lightgray">
      <h4 class="uppercase-box"><?= __('app.websites'); ?></h4>
      <ul>
        <?php foreach ($data['items'] as $item) : ?>
          <li class="mt15">
            <a href="<?= url('website', ['slug' => $item['item_domain']]); ?>">
              <?= $item['item_title']; ?> <span class="green"><?= $item['item_domain']; ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="sticky top-sm">
    <?= insert('/_block/latest-answers-tabs', ['latest_answers' => $data['latest_answers']]); ?>

    <?php if (UserData::getUserScroll()) : ?>
      <?= insert('/global/sidebar-footer'); ?>
    <?php endif; ?>
  </div>
</aside>