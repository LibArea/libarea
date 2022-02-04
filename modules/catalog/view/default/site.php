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
          <?= html_facet($item['facet_list'], 'topic', 'web.topic', 'tag mr15'); ?>
          <?php if (UserData::checkAdmin()) { ?>
            <a href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
              <i class="bi bi-pencil text-sm"></i>
            </a> <small class="gray-400">- <?= $item['item_following_link']; ?></small>
          <?php } ?>
          <div class="list-items__text">
            <?= $item['item_content_url']; ?>
          </div>
          <div class="list-items__footer">
            <div class="green-600">
              <?= website_img($item['item_url_domain'], 'favicon', $item['item_url_domain'], 'favicons mr5'); ?>
              <?= $item['item_url_domain']; ?>
              <?php if ($item['item_github_url']) { ?>
                <a class="ml15 gray-600 mb-none" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                  <i class="bi bi-github text-sm mr5"></i>
                  <?= $item['item_title_soft']; ?> <?= Translate::get('on'); ?> GitHub
                </a>
              <?php } ?>
              <div>
                <i class="bi bi-arrow-return-right gray-600 ml10"></i>
                <a class="black" href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
                  <?= Translate::get('more.detailed'); ?>
                </a>
              </div>
            </div>
            <div class="flex right gray-400">
              <?= favorite($user['id'], $item['item_id'], 'item', $item['favorite_tid'], 'ps', 'mr20'); ?>
              <?= votes($user['id'], $item, 'item', 'ps', 'mr5'); ?>
            </div>
          </div>
        </div>
      </li>
    <?php } else { ?>
      <?php if (UserData::checkAdmin()) { ?>
        <div class="mt15 mb15">
          <i class="bi bi-link-45deg red mr5 text-2xl"></i>
          <?= $item['item_title_url']; ?> (<?= $item['item_url_domain']; ?>)
          <a class="ml15" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
            <i class="bi bi-pencil"></i>
          </a>
        </div>
      <?php } ?>
    <?php } ?>
  <?php } ?>
</ol>