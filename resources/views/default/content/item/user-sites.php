<div id="contentWrapper">
  <main>
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
            <div class="content">
              <div class="list-items__thumb mb-none">
                <?= Img::website($item['item_domain'], 'thumbs', $item['item_title'], 'list-items__thumb-image'); ?>
              </div>
              <div class="list-items__description">
                <?php if ($item['item_published'] == 0) : ?>
                  <span class="label label-orange mr15">
                    <?= __('web.moderation'); ?>
                  </span>
                <?php endif; ?>
                <?= Img::facets($item['facet_list'], 'category', 'tag mr15', 'all'); ?>

                <?php if (Access::author('item', $item['item_user_id'], $item['item_date'], 30) === true) : ?>
                  <a href="<?= url('content.edit', ['type' => 'item', 'id' => $item['item_id']]); ?>">
                    <svg class="icons">
                      <use xlink:href="/assets/svg/icons.svg#edit"></use>
                    </svg>
                  </a> - <?= $item['item_following_link']; ?>
                <?php endif; ?>

                <div class="list-items__text">
                  <?= Content::fragment(Content::text($item['item_content'], 'line'), 200); ?>
                </div>
                <div class="list-items__footer">
                  <div class="green">
                    <?= Img::website($item['item_domain'], 'favicon', $item['item_domain'], 'favicons mr5'); ?>
                    <?= $item['item_domain']; ?>
                    <?php if ($item['item_github_url']) : ?>
                      <a class="ml15 gray-600 mb-none" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                        <svg class="icons">
                          <use xlink:href="/assets/svg/icons.svg#github"></use>
                        </svg>
                        <?= $item['item_title_soft']; ?>
                      </a>
                    <?php endif; ?>
                    <?php if ($item['item_published'] == 1) : ?>
                      <div>
                        <svg class="icons">
                          <use xlink:href="/assets/svg/icons.svg#corner-down-right"></use>
                        </svg>
                        <a class="black" href="<?= url('website', ['slug' => $item['item_domain']]); ?>">
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
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box text-sm bg-lightgray mt15">
        <h4 class="uppercase-box"><?= __('web.menu'); ?></h4>
        <ul class="menu">
          <?= insert('/_block/navigation/item/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>