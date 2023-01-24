<?php

use Hleb\Constructor\Handlers\Request; ?>

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

  <?php if ($data['sheet'] == 'top') : ?>
    <?php $day = Request::getGet('sort_day'); ?>
    <div class="mb20 text-sm">
      <a class="ml10 gray-600 <?php if ($day == 1) : ?> active<?php endif; ?>" href="./top?sort_day=1"><?= __('app.one_month'); ?></a>
      <a class="mr15 ml15 gray-600<?php if ($day == 3) : ?> active<?php endif; ?>" href="./top?sort_day=3"><?= __('app.three_months'); ?></a>
      <a class="gray-600<?php if ($day == 'all' || !$day) : ?> active<?php endif; ?>" href="./top?sort_day=all"><?= __('app.all_time'); ?></a>
    </div>
  <?php endif; ?>

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
    <div class="box bg-lightgray text-sm">
      <h4 class="uppercase-box"><?= __('app.authorization'); ?></h4>
      <form class="max-w300" action="<?= url('enterLogin'); ?>" method="post">
        <?php csrf_field(); ?>
        <?= insert('/_block/form/login'); ?>
        <fieldset class="gray-600 center">
          <?= __('app.agree_rules'); ?>
          <a href="<?= url('recover'); ?>"><?= __('app.forgot_password'); ?>?</a>
        </fieldset>
      </form>
    </div>
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