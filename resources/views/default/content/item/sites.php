<main class="col-span-12 mb-col-12">
  <div class="pt5 mr15 pb5 ml15 size-13">
    <?php if ($uid['user_trust_level'] == 5) { ?>
      <a title="<?= Translate::get('add'); ?>" class="right mt5" href="<?= getUrlByName('sites.add'); ?>">
        <i class="bi bi-plus-lg middle"></i>
      </a>
    <?php } ?>
    <a class="gray" href="<?= getUrlByName('web'); ?>" class="size-14 gray-light-2"><?= Translate::get('sites'); ?></a>

    <?php if (!empty($data['high_topics'][0])) {
      $site = $data['high_topics'][0];   ?>
      <span class="gray mr5 ml5">/</span>
      <a class="" href="<?= getUrlByName('web.topic', ['slug' => $site['facet_slug']]); ?>" title="<?= $site['facet_title']; ?>">
        <?= $site['facet_title']; ?>
      </a>
    <?php } ?>
    <h1 class="mt0 mb5 size-24 font-normal">
      <?= $data['topic']['facet_title']; ?>
      <?php if ($uid['user_trust_level'] == 5) { ?>
        <a class="size-14 ml5" href="<?= getUrlByName('topic.edit', ['id' => $data['topic']['facet_id']]); ?>">
          <sup><i class="bi bi-pencil gray"></i></sup>
        </a>
        <a class="size-14 ml15" href="<?= getUrlByName('topic', ['slug' => $data['topic']['facet_slug']]); ?>">
          <sup><i class="bi bi-columns-gap gray"></i></sup>
        </a>
      <?php } ?>
    </h1>
  </div>

  <div class="mb20 pt10 pr15 pb10 pl15 bg-green-200 dark-bg-black">
    <?php if ($data['low_topics']) { ?>
      <div class="grid grid-cols-3 gap-2 justify-between mb10">
        <?php foreach ($data['low_topics'] as $lt) { ?>
          <?php if ($lt['facet_is_web'] == 1) { ?>
            <div>
              <a class="pt5 pr10 underline-hover mb-col-2 mb-size-18 size-21 inline" href="<?= getUrlByName('web.topic', ['slug' => $lt['facet_slug']]); ?>">
                <?= $lt['facet_title']; ?>
              </a>
              <?php if ($uid['user_trust_level'] == 5) { ?>
                <a class="size-14 " href="<?= getUrlByName('topic.edit', ['id' => $lt['value']]); ?>">
                  <sup><i class="bi bi-pencil gray"></i></sup>
                </a>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>
    <?php if ($data['low_matching']) { ?>
      <div class="mt15 mb10">
        <div class="gray size-14"><?= Translate::get('see more'); ?></div>
        <?php foreach ($data['low_matching'] as $rl) { ?>
          <?php if ($rl['facet_is_web'] == 1) { ?>
            <div class="inline mr20">
              <a class="underline-hover" href="<?= getUrlByName('web.topic', ['slug' => $rl['facet_slug']]); ?>">
                <?= $rl['facet_title']; ?>
              </a>
              <?php if ($uid['user_trust_level'] == 5) { ?>
                <a class="size-14 ml5" href="<?= getUrlByName('topic.edit', ['id' => $rl['value']]); ?>">
                  <sup><i class="bi bi-pencil gray"></i></sup>
                </a>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>
  </div>

  <div class="flex flex-row items-center justify-between pt5 mr15 pb5 ml15 w-90">
    <p class="m0 size-18 lowercase"><?= num_word($data['count'], Translate::get('num-website'), true); ?></p>
    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        false,
        $pages = [
          [
            'id' => 'all',
            'url' => getUrlByName('web.topic', ['slug' => $data['topic']['facet_slug']]),
            'content' => Translate::get('all'),
            'icon' => 'bi bi-app'
          ],
          [
            'id' => 'new',
            'url' => getUrlByName('web.topic.new', ['slug' => $data['topic']['facet_slug']]),
            'content' => Translate::get('new ones'),
            'icon' => 'bi bi-bar-chart'
          ],
        ]
      ); ?>

    </ul>
  </div>
  <div class="pt5 mr15 pb5 ml15">
    <?php if (!empty($data['items'])) { ?>
      <?php foreach ($data['items'] as $key => $item) { ?>
        <div class="pt20 pb5 flex flex-row gap-2">
          <div class="mr20 w200 no-mob">
            <?= thumbs_img($item['item_url_domain'], $item['item_title_url'], 'mr5 w200 box-shadow'); ?>
          </div>
          <div class="mr20 flex-auto">
            <a class="dark-white" href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
              <h2 class="font-normal underline-hover size-21 mt0 mb0">
                <?= $item['item_title_url']; ?>
              </h2>
            </a>
            <div class="size-15 mt5 mb15 max-w780">
              <?= $item['item_content_url']; ?>
            </div>
            <div class="flex flex-row gap-2 items-center max-w780">
              <?= favicon_img($item['item_id'], $item['item_url_domain']); ?>
              <div class="green size-140 size-14">
                <?= $item['item_url_domain']; ?>
                <?php if ($item['item_github_url']) { ?>
                  <a class="ml15 gray-light" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
                    <i class="bi bi-github size-13 mr5"></i>
                    <?= $item['item_title_soft']; ?> <?= Translate::get('on'); ?> GitHub
                  </a>
                <?php } ?>
                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a class="ml15 inline" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
                    <i class="bi bi-pencil size-13"></i>
                  </a>
                <?php } ?>
                <div class="lowercase">
                  <?= html_topic($item['facet_list'], 'web.topic', 'gray-light mr15'); ?>
                </div>
              </div>
              <div class="hidden lowercase ml-auto pr10">
                <?= votes($uid['user_id'], $item, 'item', 'mr5'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('web')); ?>
  </div>
</main>
</div>
<div class="bg-white p15 no-mob br-box-gray">
  <?= $data['topic']['facet_description']; ?>
</div>

<?= import('/_block/wide-footer'); ?>