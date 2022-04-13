<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<div id="contentWrapper">
  <main>
    <h2 class="mb20">
      <?= __($data['sheet'] . '.view'); ?>
      <?php if ($data['count'] != 0) : ?><sup class="gray-600 text-sm"><?= $data['count']; ?></sup><?php endif; ?>
    </h2>

    <?php if (!empty($data['items'])) : ?>
      <ol class="list-items">
        <?php foreach ($data['items'] as $key => $item) : ?>

          <li>
            <div class="list-items__thumb mb-none">
              <?= Html::websiteImage($item['item_domain'], 'thumbs', $item['item_title'], 'list-items__thumb-image'); ?>
            </div>
            <div class="list-items__description">
              <a target="_blank" class="item_cleek" rel="nofollow noreferrer ugc" data-id="<?= $item['item_id']; ?>" href="<?= $item['item_url']; ?>">
                <h2><?= $item['item_title']; ?></h2>
              </a>
              <?php if ($item['item_published'] == 0) : ?>
                <span class="focus-id bg-violet text-sm">
                  <?= __('moderation'); ?>
                </span>
              <?php endif; ?>
              <?= Html::facets($item['facet_list'], 'category', 'web.dir', 'tags mr15', 'all'); ?>

              <?php if (Html::accessСheck($item, 'item', $user, false, false) === true) : ?>
                <a href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
                  <i class="bi-pencil text-sm"></i>
                </a> - <?= $item['item_following_link']; ?>
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
                  <?php if ($item['item_published'] == 1) : ?>
                    <div>
                      <i class="bi-arrow-return-right gray-600 ml10"></i>
                      <a class="black" href="<?= getUrlByName('web.website', ['slug' => $item['item_domain']]); ?>">
                        <?= __('more.detailed'); ?>
                      </a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>
    <?php else : ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
    <?php endif ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
  </main>
  <aside>
    <div class="box bg-yellow text-sm mt15"><?= __('user.sites.info'); ?>.</div>
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box text-sm bg-violet mt15">
        <h3 class="uppercase-box"><?= __('menu'); ?></h3>
        <ul class="menu">
          <?= includeTemplate('/view/default/_block/add-site', ['user' => $user, 'data' => $data]); ?>
          <?= Tpl::insert('/_block/navigation/menu', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('catalog/menu.user')]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>