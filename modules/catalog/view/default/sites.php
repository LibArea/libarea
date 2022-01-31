<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

    <?php if (UserData::checkAdmin()) { ?>
      <a title="<?= Translate::get('add'); ?>" class="right mt5" href="<?= getUrlByName('site.add'); ?>">
        <i class="bi bi-plus-lg middle"></i>
      </a>
    <?php } ?>
    <a href="<?= getUrlByName('web'); ?>" class="text-sm gray-400 ml10"><?= Translate::get('websites'); ?></a> 

    <?php if (!empty($data['high_topics'][0])) {
      $site = $data['high_topics'][0];   ?>
      <span class="gray mr5 ml5">/</span>
      <a href="<?= getUrlByName('web.topic', ['slug' => $site['facet_slug']]); ?>">
        <?= $site['facet_title']; ?>
      </a>
    <?php } ?>
    <h1 class="ml10">
      <?= $data['topic']['facet_title']; ?>
      <?php if (UserData::checkAdmin()) { ?>
        <a class="text-sm ml5" href="<?= getUrlByName('topic.edit', ['id' => $data['topic']['facet_id']]); ?>">
          <sup><i class="bi bi-pencil gray"></i></sup>
        </a>
        <a class="text-sm ml15" href="<?= getUrlByName('topic', ['slug' => $data['topic']['facet_slug']]); ?>">
          <sup><i class="bi bi-columns-gap gray"></i></sup>
        </a>
      <?php } ?>
    </h1>

  <div class="mb20 p15 bg-yellow-50">
    <?php if ($data['low_topics']) { ?>
      <div class="grid grid-cols-3 gap-2 justify-between mb10">
        <?php foreach ($data['low_topics'] as $lt) { ?>
          <?php if ($lt['facet_is_web'] == 1) { ?>
            <div>
              <a class="pt5 pr10 underline-hover mb-col-2 mb-text-xl text-2xl inline" href="<?= getUrlByName('web.topic', ['slug' => $lt['facet_slug']]); ?>">
                <?= $lt['facet_title']; ?>
              </a>
              <?php if (UserData::checkAdmin()) { ?>
                <a class="text-sm" href="<?= getUrlByName('topic.edit', ['id' => $lt['value']]); ?>">
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
        <div class="gray text-sm"><?= Translate::get('see more'); ?></div>
        <?php foreach ($data['low_matching'] as $rl) { ?>
          <?php if ($rl['facet_is_web'] == 1) { ?>
            <div class="inline mr20">
              <a class="underline-hover" href="<?= getUrlByName('web.topic', ['slug' => $rl['facet_slug']]); ?>">
                <?= $rl['facet_title']; ?>
              </a>
              <?php if (UserData::checkAdmin()) { ?>
                <a class="text-sm ml5" href="<?= getUrlByName('topic.edit', ['id' => $rl['value']]); ?>">
                  <sup><i class="bi bi-pencil gray"></i></sup>
                </a>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
  
<div class="grid grid-cols-12 gap-4">
  <main class="col-span-9 mb-col-12 ml15">
  <div class="box-flex max-w780">
    <p class="m0 text-xl lowercase">
      <?= num_word($data['count'], Translate::get('num-website'), true); ?>
    </p>
    <ul class="flex flex-row list-none m0 p0 center">
 
      <?= tabs_nav(
        $user['id'],
        $data['sheet'],
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
  <div class="ml15">
    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('web')); ?>
</main>
<aside class="col-span-3 mb-col-12 relative mb-none">
  <?= $data['topic']['facet_description']; ?>
</aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>