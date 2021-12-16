<main class="col-span-12 mb-col-12">
  <div class="pt5 mr15 pb5 ml15">
    <?php if ($uid['user_trust_level'] == 5) { ?>
      <a title="<?= Translate::get('add'); ?>" class="right mt5" href="<?= getUrlByName('sites.add'); ?>">
        <i class="bi bi-plus-lg middle"></i>
      </a>
    <?php } ?>
    <h1 class="mt5 mb10 size-24 font-normal"><?= Translate::get('domains-title'); ?></h1>
  </div>
  <div class="flex mb20 pt10 pr15 pb10 pl15 bg-green-200 flex-auto">
    <?php foreach (Config::get('web-root-categories') as  $cat) { ?>
      <div class="mr60">
        <a class="pt5 pr10 mr60 dark-white underline-hover size-21 block " title="<?= $cat['title']; ?>" href="<?= getUrlByName('web.topic', ['slug' => $cat['url']]); ?>">
          <?= $cat['title']; ?>
        </a>
        <?php if (!empty($cat['sub'])) { ?>
          <?php foreach ($cat['sub'] as $sub) { ?>
            <a class="pr10 pb5 size-14 black inline" title="<?= $sub['title']; ?>" href="<?= getUrlByName('web.topic', ['slug' => $sub['url']]); ?>">
              <?= $sub['title']; ?>
            </a>
          <?php } ?>
        <?php } ?>
        <?php if (!empty($cat['help'])) { ?>
          <div class="size-13 gray-light-2 mt5"><?= $cat['help']; ?>...</div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
  <div class="pt5 mr15 pb5 ml15">
    <?php if (!empty($data['items'])) { ?>
      <?php foreach ($data['items'] as $key => $item) { ?>
        <?php if ($item['item_published'] == 1) { ?>
          <div class="pt20 pb5 flex flex-row gap-2">
            <div class="mr20 w200 no-mob">
              <?= thumbs_img($item['item_url_domain'], $item['item_title_url'], 'mr5 mt5 w200 box-shadow'); ?>
            </div>
            <div class="w-100">
              <a class="pt0 pr5 dark-white size-21" href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
                <h2 class="font-normal inline underline-hover size-21 mt0 mb0">
                  <?= $item['item_title_url']; ?>
                </h2>
              </a>
              <?php if ($uid['user_trust_level'] == 5) { ?>
                <a class="ml15" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
                  <i class="bi bi-pencil size-15"></i>
                </a>
              <?php } ?>
              <div class="size-15 mt5 mb15 max-w780">
                <?= $item['item_content_url']; ?>
              </div>
              <div class="flex flex-row gap-2 items-center max-w780">
                <?= favicon_img($item['item_id'], $item['item_url_domain']); ?>
                <div class="green size-14">
                 
                  <?= $item['item_url_domain']; ?>
                  
  <?php if ($item['item_github_url']) { ?>
                    <a class="ml15 gray-light" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                      <i class="bi bi-github size-13 mr5"></i>
                      <?= $item['item_title_soft']; ?> на GitHub
                    </a>
                  <?php } ?>
                  
                  <div class="lowercase">
                    <?= html_topic($item['facet_list'], 'web.topic', 'gray-light mr15'); ?>
                  </div>
                </div>
                <div class="hidden lowercase ml-auto">
                  <?= votes($uid['user_id'], $item, 'item', 'mr5'); ?>
                </div>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <?php if ($uid['user_trust_level'] == 5) { ?>
            <div class="mt15 mb15">
              <i class="bi bi-link-45deg red mr5 size-18"></i>
              <?= $item['item_title_url']; ?> (<?= $item['item_url_domain']; ?>)
              <a class="ml15" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
                <i class="bi bi-pencil size-15"></i>
              </a>
            </div>
          <?php } ?>
        <?php } ?>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <div class="pl10">
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('web')); ?>
  </div>
</main>
<?= includeTemplate('/_block/wide-footer'); ?>