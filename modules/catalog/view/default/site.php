<ol class="list-items">
  <?php foreach ($data['items'] as $key => $item) { ?>

    <?php if ($item['item_published'] == 1) { ?>
      <li>
        <div class="list-items__thumb mb-none">
          <?= website_img($item['item_url_domain'], 'thumbs', $item['item_title_url'], 'list-items__thumb-image'); ?>
        </div>
        <div class="list-items__description">
          <a href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
            <h2><?= $item['item_title_url']; ?></h2>
          </a>
          <?= html_facet($item['facet_list'], 'topic', 'web.topic', 'tag mr15'); ?>
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
              <?php if (UserData::checkAdmin()) { ?>
                <a class="mr15 ml15 inline" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
                  <i class="bi bi-pencil text-sm"></i>
                </a>
              <?php } ?>
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