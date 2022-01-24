<?php foreach ($data['items'] as $key => $item) { ?>
<?php if ($item['item_published'] == 1) { ?>
<div class="pt20 pb5 flex flex-row gap-2">
  <div class="mr20 w200 mb-none">
    <?= website_img($item['item_url_domain'], 'thumbs', $item['item_title_url'], 'mr5 w200 box-shadow'); ?>
  </div>
  <div class="mr20 flex-auto">
    <a class="dark-gray-300" href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
      <h2 class="font-normal underline-hover text-2xl mt0 mb0">
        <?= $item['item_title_url']; ?>
      </h2>
    </a>
    <div class="mt5 mb15 max-w780">
      <?= $item['item_content_url']; ?>
    </div>
    <div class="flex flex-row gap-2 items-center max-w780">
      <?= website_img($item['item_url_domain'], 'favicon', $item['item_url_domain'], 'mr5 w20 h20'); ?>
      <div class="green-600 text-sm">
        <?= $item['item_url_domain']; ?>
        <?php if ($item['item_github_url']) { ?>
          <a class="ml15 gray-600" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
            <i class="bi bi-github text-sm mr5"></i>
            <?= $item['item_title_soft']; ?> <?= Translate::get('on'); ?> GitHub
          </a>
        <?php } ?>
        <?php if (UserData::checkAdmin()) { ?>
          <a class="ml15 inline" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
            <i class="bi bi-pencil text-sm"></i>
          </a>
        <?php } ?>
        <div class="lowercase">
          <?= html_facet($item['facet_list'], 'web.topic', 'gray-600 mr15'); ?>
        </div>
      </div>
      <div class="hidden lowercase ml-auto pr10">
        <?= votes($user['id'], $item, 'item', 'ps', 'mr5'); ?>
      </div>
    </div>
  </div>
</div>
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
