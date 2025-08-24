<main>
  <div class="nav-bar">
    <ul class="nav scroll-menu">
      <?= insert('/_block/navigation/config/home-nav'); ?>
    </ul>
    <div class="relative">
      <?= insert('/_block/navigation/sorting-day'); ?>
    </div>
  </div>

  <?php if (!$container->user()->active()) : ?>
    <div class="banner mt20">
      <h1><?= config('meta', 'banner_title'); ?></h1>
      <p><?= config('meta', 'banner_desc'); ?>...</p>
    </div>
  <?php endif; ?>

  <?= insert('/content/publications/choice', ['data' => $data]); ?>

  <?php if ($container->user()->scroll()) : ?>
    <div id="scrollArea"></div>
    <div id="scroll"></div>
  <?php else : ?>
    <?php
    $sort = $container->request()->get('sort')->value();
    $sort = $sort ? '&sort=' . $sort : '';
    ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null, '?', $sort); ?>
  <?php endif; ?>
</main>

<aside>
  <?= insert('/banner/home-sidebar-240-400'); ?>

  <?php if (is_array($data['topics'])) : ?>
    <?php if (count($data['topics']) > 0) : ?>
      <div class="box">
        <h4 class="uppercase-box"><?= __('app.recommended'); ?></h4>
        <ul>
          <?php foreach ($data['topics'] as $recomm) : ?>
            <li class="flex justify-between items-center mb10">
              <a class="flex items-center gap-sm" href="<?= url('topic', ['slug' => $recomm['facet_slug']]); ?>">
                <?= Img::image($recomm['facet_img'], '', 'img-sm', 'logo', 'small'); ?>
                <?= $recomm['facet_title']; ?>
              </a>
              <?php if ($container->user()->id()) : ?>
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

  <?= insert('/_block/latest-comments-tabs', ['latest_comments' => $data['latest_comments']]); ?>
</aside>