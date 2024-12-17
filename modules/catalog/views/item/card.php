<ol itemscope itemtype="https://schema.org/ItemList" class="list-items">
  <?php foreach ($data['items'] as $item) :
    $parse  =  parse_url($item['item_url']); ?>

    <?php if ($item['item_published'] == 1) : ?>
      <li itemscope itemprop="itemListElement" itemtype="https://schema.org/WebSite">
        <a target="_blank" class="item_cleek" rel="nofollow noreferrer ugc" data-id="<?= $item['item_id']; ?>" href="<?= $item['item_url']; ?>">
          <h3 class="title" itemprop="name"><?= $item['item_title']; ?>
            <?php $date = date_diff(new DateTime(), new DateTime($item['item_date']))->days; ?>
            <?php if ($date < 3) : ?><sup class="red text-sm">new</sup><?php endif; ?></h3>
        </a>
        <div class="flex gap">
          <div class="mb-none img-preview">
            <?= Img::website('thumb', host($item['item_url']), 'list-items__thumb-image'); ?>
          </div>
          <div class="list-items__description">
            <?= Html::facets($item['facet_list'], 'category', 'tag-violet mr15', $sort); ?>

            <?php if ($container->access()->author('item', $item) === true) : ?>
              <a href="<?= url('item.form.edit', ['id' => $item['item_id']]); ?>">
                <svg class="icon">
                  <use xlink:href="/assets/svg/icons.svg#edit"></use>
                </svg>
              </a>
              <span class="gray-600">- <?= $item['item_following_link']; ?></span>
            <?php endif; ?>

            <?php if ($container->user()->admin()) : ?>
              <a data-type="item" data-id="<?= $item['item_id']; ?>" class="type-action gray-600 lowercase ml15"><?= __('app.remove'); ?> (<?= __('app.website'); ?>)</a>
            <?php endif; ?>

            <?php if ($item['item_is_deleted'] == 1 && $container->user()->admin()) : ?>
              <i class="red ml15"><?= __('app.remote'); ?></i>
            <?php endif; ?>

            <?php if (!empty($delete_fav)) : ?>
              <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $item['item_id']; ?>" data-type="website">
                <svg class="icon gray-600">
                  <use xlink:href="/assets/svg/icons.svg#trash"></use>
                </svg>
              </span>
            <?php endif; ?>
            <div itemprop="description">
              <?= fragment($item['item_content'], 200); ?>
            </div>
            <div class="list-items__footer">
              <div class="green">
                <?= Img::website('favicon', host($item['item_url']), 'favicons mr5'); ?>
                <?= host($item['item_url']); ?>
                <?php if ($item['item_github_url']) : ?>
                  <a class="ml15 gray-600 mb-none" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                    <svg class="icon">
                      <use xlink:href="/assets/svg/icons.svg#github"></use>
                    </svg>
                    <?= $item['item_title_soft']; ?>
                  </a>
                <?php endif; ?>
                <?php if ($item['item_telephone']) : ?>
                  <span class="gray-600 ml5 mb-none"><?= $item['item_telephone']; ?></span>
                <?php endif; ?>
                <div>
                  <svg class="icon gray-600 ml5">
                    <use xlink:href="/assets/svg/icons.svg#corner-down-right"></use>
                  </svg>
                  <a itemprop="url" class="lowercase" href="<?= url('website', ['id' => $item['item_id'], 'slug' => $item['item_slug'] ?? 'slug']); ?>">
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
      <?php if ($container->user()->admin()) : ?>
        <div class="mt15 mb15">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#link"></use>
          </svg>
          <?= $item['item_title']; ?> (<?= $item['item_domain']; ?>)
          <a class="ml15" href="<?= url('item.form.edit', ['id' => $item['item_id']]); ?>">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>
</ol>