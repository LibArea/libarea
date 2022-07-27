<ol class="list-items">
  <?php foreach ($data['items'] as $item) : ?>

    <?php if ($item['item_published'] == 1) : ?>
      <li>
        <a target="_blank" class="item_cleek" rel="nofollow noreferrer ugc" data-id="<?= $item['item_id']; ?>" href="<?= $item['item_url']; ?>">
          <h2 class="m0"><?= $item['item_title']; ?>
            <?php $date = date_diff(new DateTime(), new DateTime($item['item_date']))->days; ?>
            <?php if ($date < 3) : ?><sup class="red text-sm">new</sup><?php endif; ?></h2>
        </a>
        <div class="content">
          <div class="list-items__thumb mb-none img-preview">
            <?= Html::websiteImage($item['item_domain'], 'thumbs', $item['item_title'], 'list-items__thumb-image'); ?>
          </div>
          <div class="list-items__description">
            <?= Html::facets($item['facet_list'], 'category', 'web.dir', 'tag mr15', $screening); ?>

            <?php if (Access::author('item', $item['item_user_id'], $item['item_date'], 30) === true) : ?>
              <a href="<?= url('content.edit', ['type' => 'item', 'id' => $item['item_id']]); ?>">
                <svg class="icons">
                  <use xlink:href="/assets/svg/icons.svg#edit"></use>
                </svg>
              </a> - <?= $item['item_following_link']; ?>
            <?php endif; ?>

            <?php if (UserData::checkAdmin()) : ?>
              <a data-type="item" data-id="<?= $item['item_id']; ?>" class="type-action gray-600 lowercase ml15"><?= __('app.remove'); ?> (<?= __('app.website'); ?>)</a>
            <?php endif; ?>

            <?php if ($item['item_is_deleted'] == 1 && UserData::checkAdmin()) : ?>
              <i class="red ml15"><?= __('app.remote'); ?></i>
            <?php endif; ?>

            <?php if (!empty($delete_fav)) : ?>
              <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $item['item_id']; ?>" data-type="website">
                <svg class="icons gray-600">
                  <use xlink:href="/assets/svg/icons.svg#trash"></use>
                </svg>
              </span>
            <?php endif; ?>
            <div class="list-items__text">
              <?= Content::fragment(Content::text($item['item_content'], 'line'), 200); ?>
            </div>
            <div class="list-items__footer">
              <div class="green">
                <?= Html::websiteImage($item['item_domain'], 'favicon', $item['item_domain'], 'favicons mr5'); ?>
                <?= $item['item_domain']; ?>
                <?php if ($item['item_github_url']) : ?>
                  <a class="ml15 gray-600 mb-none" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                    <svg class="icons">
                      <use xlink:href="/assets/svg/icons.svg#github"></use>
                    </svg>
                    <?= $item['item_title_soft']; ?>
                  </a>
                <?php endif; ?>
                <div>
                  <svg class="icons gray ml5">
                    <use xlink:href="/assets/svg/icons.svg#corner-down-right"></use>
                  </svg>
                  <a class="black " href="<?= url('website', ['slug' => $item['item_domain']]); ?>">
                    <?= __('web.more'); ?>
                  </a>
                </div>
              </div>
              <div class="flex gap right gray-600">
                <?= Html::favorite($item['item_id'], 'website', $item['tid']); ?>
                <?= Html::votes($item, 'item'); ?>
              </div>
            </div>
          </div>
        </div>
      </li>
    <?php else : ?>
      <?php if (UserData::checkAdmin()) : ?>
        <div class="mt15 mb15">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#link"></use>
          </svg>
          <?= $item['item_title']; ?> (<?= $item['item_domain']; ?>)
          <a class="ml15" href="<?= url('content.edit', ['type' => 'item', 'id' => $item['item_id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>
</ol>