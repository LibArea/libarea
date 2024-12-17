<?= insertTemplate('header', ['meta' => $meta]); ?>

<div id="contentWrapper" class="wrap">
  <main class="w-100">
    <h2 class="mb20">
      <?= __('web.my_website'); ?>
      <?php if ($data['count'] != 0) : ?><sup class="gray-600 text-sm"><?= $data['count']; ?></sup><?php endif; ?>
    </h2>

    <?php if (!empty($data['items'])) : ?>
      <ol class="list-items">
        <?php foreach ($data['items'] as $key => $item) : ?>
          <li>
            <a target="_blank" class="item_cleek" rel="nofollow noreferrer ugc" data-id="<?= $item['item_id']; ?>" href="<?= $item['item_url']; ?>">
              <h3 class="title"><?= $item['item_title']; ?></h3>
            </a>
            <div class="flex gap">
              <div class="mb-none">
                <?= Img::website('thumb', host($item['item_url']), 'list-items__thumb-image'); ?>
              </div>
              <div class="list-items__description">
                <?php if ($item['item_published'] == 0) : ?>
                  <span class="label label-orange mr15">
                    <?= __('web.moderation'); ?>
                  </span>
                <?php endif; ?>
                <?= Html::facets($item['facet_list'], 'category', 'tag-violet mr15', 'all'); ?>

                <?php if ($container->access()->author('item', $item) === true) : ?>
                  <a href="<?= url('item.form.edit', ['id' => $item['item_id']]); ?>">
                    <svg class="icon">
                      <use xlink:href="/assets/svg/icons.svg#edit"></use>
                    </svg>
                  </a>
                  <span class="gray-600">- <?= $item['item_following_link']; ?></span>
                <?php endif; ?>

                <div class="black">
                  <?= fragment($item['item_content'], 200); ?>
                </div>
                <div class="list-items__footer">
                  <div class="green">
                    <?= Img::website('favicon', host($item['item_url']), 'favicons mr5'); ?>
                    <?= $item['item_domain']; ?>
                    <?php if ($item['item_github_url']) : ?>
                      <a class="ml15 gray-600 mb-none" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                        <svg class="icon">
                          <use xlink:href="/assets/svg/icons.svg#github"></use>
                        </svg>
                        <?= $item['item_title_soft']; ?>
                      </a>
                    <?php endif; ?>
                    <?php if ($item['item_published'] == 1) : ?>
                      <div>
                        <svg class="icon gray-600 ml5">
                          <use xlink:href="/assets/svg/icons.svg#corner-down-right"></use>
                        </svg>
                        <a class="lowercase" href="<?= url('website', ['id' => $item['item_id'], 'slug' => $item['item_slug']]); ?>">
                          <?= __('web.more'); ?>
                        </a>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no'), 'icon' => 'info']); ?>
    <?php endif ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/web/my'); ?>
  </main>
  <aside>
    <div class="box bg-beige text-sm mt15"><?= __('web.my_website_info'); ?>.</div>
    <?php if ($container->user()->active()) : ?>
      <div class="box text-sm bg-lightgray mt15">
        <h4 class="uppercase-box"><?= __('web.menu'); ?></h4>
        <ul class="menu">
          <?= insertTemplate('/navigation/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>