<ol class="list-items">
  <?php foreach ($data['items'] as $key => $item) { ?>

    <?php if ($item['item_published'] == 1) { ?>
      <li>
        <div class="list-items__thumb mb-none">
          <?= website_img($item['item_url_domain'], 'thumbs', $item['item_title_url'], 'list-items__thumb-image'); ?>
        </div>
        <div class="list-items__description">
          <a target="_blank" class="item_cleek" rel="nofollow noreferrer ugc" data-id="<?= $item['item_id']; ?>" href="<?= $item['item_url']; ?>">
            <h2><?= $item['item_title_url']; ?></h2>
          </a>
          <?= facets($item['facet_list'], 'category', 'web.dir', 'tags mr15', $screening); ?>

          <?php if (accessСheck($item, 'item', $user, false, false) === true) { ?>
            <a href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
              <i class="bi-pencil text-sm"></i>
            </a> - <?= $item['item_following_link']; ?>
          <?php } ?>

          <?php if (!empty($delete_fav)) { ?>
            <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $item['item_id']; ?>" data-type="website">
              <i class="bi-trash red"></i>
            </span>
          <?php } ?>
          <div class="list-items__text">
            <?= $item['item_content_url']; ?>
          </div>
          <div class="list-items__footer">
            <div class="green">
              <?= website_img($item['item_url_domain'], 'favicon', $item['item_url_domain'], 'favicons mr5'); ?>
              <?= $item['item_url_domain']; ?>
              <?php if ($item['item_github_url']) { ?>
                <a class="ml15 gray-600 mb-none" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                  <i class="bi-github text-sm mr5"></i>
                  <?= $item['item_title_soft']; ?>
                </a>
              <?php } ?>
              <div>
                <i class="bi-arrow-return-right gray-600 ml10"></i>
                <a class="black" href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
                  <?= Translate::get('more.detailed'); ?>
                </a>
              </div>
            </div>
            <div class="flex right gray-600">
              <?= favorite($user['id'], $item['item_id'], 'website', $item['tid'], 'ps', 'mr20'); ?>
              <?= votes($user['id'], $item, 'item', 'ps', 'mr5'); ?>
            </div>
          </div>
        </div>
      </li>
    <?php } else { ?>
      <?php if (UserData::checkAdmin()) { ?>
        <div class="mt15 mb15">
          <i class="bi-link-45deg red mr5 text-2xl"></i>
          <?= $item['item_title_url']; ?> (<?= $item['item_url_domain']; ?>)
          <a class="ml15" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
            <i class="bi-pencil"></i>
          </a>
        </div>
      <?php } ?>
    <?php } ?>
  <?php } ?>
</ol>