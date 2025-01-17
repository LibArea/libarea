<main>
  <div class="nav-bar">
    <ul class="nav scroll-menu">
      <?= insert('/_block/navigation/config/home-nav'); ?>
    </ul>
    <div class="relative">
      <?= insert('/_block/navigation/sorting-day'); ?>
    </div>
  </div>

  <?= insert('/content/post/type-post', ['data' => $data]); ?>

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
  <?php if (!$container->user()->active()) : ?>
    <div class="box text-sm">
      <h4 class="uppercase-box"><?= __('app.authorization'); ?></h4>
      <form class="max-w-sm" action="<?= url('authorization', method: 'post'); ?>" method="post">
        <?= $container->csrf()->field(); ?>
        <?= insert('/_block/form/login'); ?>
        <fieldset class="gray-600 center">
          <?= __('app.agree_rules'); ?>
          <a href="<?= url('recover'); ?>"><?= __('app.forgot_password'); ?>?</a>
        </fieldset>
      </form>
    </div>
  <?php endif; ?>

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

  <?php if ($block = $topics_user[0] ?? false) : ?>
    <?php if ($block['type'] == 2) : ?>
      <div class="box">
        <h4 class="uppercase-box"><?= __('app.websites'); ?></h4>
        <ul>
          <?php foreach ($data['items'] as $item) : ?>
            <li class="mt15">
              <a href="<?= url('website', ['id' => $item['item_id'], 'slug' => $item['item_slug']]); ?>">
                <?= $item['item_title']; ?> <span class="green"><?= $item['item_domain']; ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <div class="sticky top-sm">
    <?= insert('/_block/latest-comments-tabs', ['latest_comments' => $data['latest_comments']]); ?>
  </div>
</aside>