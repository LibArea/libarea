<ol class="list-items">
  <?php foreach ($data['items'] as $item) : ?>

    <?php if ($item['item_published'] == 1) : ?>
      <li>
        <div class="list-items__thumb mb-none">
          <?= Html::websiteImage($item['item_domain'], 'thumbs', $item['item_title'], 'preview list-items__thumb-image'); ?>
        </div>
        <div class="list-items__description">
          <a target="_blank" class="item_cleek" rel="nofollow noreferrer ugc" data-id="<?= $item['item_id']; ?>" href="<?= $item['item_url']; ?>">
            <h2><?= $item['item_title']; ?>
              <?php $date = date_diff(new DateTime(), new DateTime($item['item_date']))->days; ?>
              <?php if ($date < 3) : ?><sup class="red text-sm">new</sup><?php endif; ?></h2>
          </a>
          <?= Html::facets($item['facet_list'], 'category', 'web.dir', 'tags mr15', $screening); ?>

          <?php if (Access::author('item', $item['item_user_id'], $item['item_date'], 30) === true) : ?>
            <a href="<?= url('web.edit', ['id' => $item['item_id']]); ?>">
              <i class="bi-pencil text-sm"></i>
            </a> - <?= $item['item_following_link']; ?>
          <?php endif; ?>

          <?php if ($item['item_is_deleted'] == 1 && UserData::checkAdmin()) : ?>
             <i class="red ml15"><?= __('app.remote'); ?></i>
          <?php endif; ?>

          <?php if (!empty($delete_fav)) : ?>
            <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $item['item_id']; ?>" data-type="website">
              <i class="bi-trash red"></i>
            </span>
          <?php endif; ?>
          <div class="list-items__text">
            <?= Html::fragment(Content::text($item['item_content'], 'line'), 200); ?>
          </div>
          <div class="list-items__footer">
            <div class="green">
              <?= Html::websiteImage($item['item_domain'], 'favicon', $item['item_domain'], 'favicons mr5'); ?>
              <?= $item['item_domain']; ?>
              <?php if ($item['item_github_url']) : ?>
                <a class="ml15 gray-600 mb-none" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                  <i class="bi-github text-sm mr5"></i>
                  <?= $item['item_title_soft']; ?>
                </a>
              <?php endif; ?>
              <div>
                <i class="bi-arrow-return-right gray-600 ml10"></i>
                <a class="black" href="<?= url('website', ['slug' => $item['item_domain']]); ?>">
                  <?= __('web.more'); ?>
                </a>
              </div>
            </div>
            <div class="flex right gray-600">
              <?php if (UserData::checkAdmin()) : ?>
                <a data-type="item" data-id="<?= $item['item_id']; ?>" class="type-action gray-600 mr30">
                  <i title="<?= __('app.remove'); ?>" class="bi-trash"></i>
                </a>
              <?php endif; ?>
            
              <?= Html::favorite($item['item_id'], 'website', $item['tid'], 'ps', 'mr20'); ?>
              <?= Html::votes($item, 'item', 'ps', 'bi-heart mr5'); ?>
            </div>
          </div>
        </div>
      </li>
    <?php else : ?>
      <?php if (UserData::checkAdmin()) : ?>
        <div class="mt15 mb15">
          <i class="bi-link-45deg red mr5 text-2xl"></i>
          <?= $item['item_title']; ?> (<?= $item['item_domain']; ?>)
          <a class="ml15" title="<?= __('edit'); ?>" href="<?= url('web.edit', ['id' => $item['item_id']]); ?>">
            <i class="bi-pencil"></i>
          </a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>
</ol>