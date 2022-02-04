<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<div class="ml30">

  <a href="<?= getUrlByName('web.all'); ?>" class="text-sm gray-400"><?= Translate::get('websites'); ?></a>

  <?php if (!empty($data['high_topics'][0])) {
    $site = $data['high_topics'][0];   ?>
    <span class="gray mr5 ml5">/</span>
    <a href="<?= getUrlByName('web.dir.top', ['slug' => $site['facet_slug']]); ?>">
      <?= $site['facet_title']; ?>
    </a>
  <?php } ?>
  <h1>
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
</div>
<div class="item-categories mb-block">
  <?php if ($data['low_topics']) { ?>
       <?php foreach ($data['low_topics'] as $lt) { ?>
        <?php if ($lt['facet_is_web'] == 1) { ?>
          <div>
            <a class="text-2xl" href="<?= getUrlByName('web.dir.top', ['slug' => $lt['facet_slug']]); ?>">
              <?= $lt['facet_title']; ?>
            </a>
            <?php if (UserData::checkAdmin()) { ?>
              <a class="ml5" href="<?= getUrlByName('topic.edit', ['id' => $lt['value']]); ?>">
                <sup><i class="bi bi-pencil gray-400"></i></sup>
              </a>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>
   <?php } ?>
  <?php if ($data['low_matching']) { ?>
      <?php foreach ($data['low_matching'] as $rl) { ?>
        <?php if ($rl['facet_is_web'] == 1) { ?>
          <div class="inline mr20">
            <a class="text-2xl" href="<?= getUrlByName('web.dir.top', ['slug' => $rl['facet_slug']]); ?>">
              @<?= $rl['facet_title']; ?>
            </a>
            <?php if (UserData::checkAdmin()) { ?>
              <a class="text-sm ml5" href="<?= getUrlByName('topic.edit', ['id' => $rl['value']]); ?>">
                <sup><i class="bi bi-pencil gray"></i></sup>
              </a>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>
  <?php } ?>
</div>

<div class="grid grid-cols-12 gap-4">
  <main class="col-span-9 mb-col-12 ml30">
    <p>
      <?= num_word($data['count'], Translate::get('num-website'), true); ?>
      <span class="right mr30">
        <a class="<?php if ($data['sheet'] == 'web.top') { ?>bg-gray-100 p5 gray-600 <?php } ?>mr20" href="<?= getUrlByName('web.dit.top', ['slug' => $data['topic']['facet_slug']]); ?>">
          TOP
        </a>
        <a class="<?php if ($data['sheet'] == 'web.new') { ?>bg-gray-100 p5 gray-600<?php } ?>" href="<?= getUrlByName('web.dir.new', ['slug' => $data['topic']['facet_slug']]); ?>">
          <?= Translate::get('by.date'); ?>
        </a>
      </span>
    </p>

    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('web')); ?>
  </main>
  <aside class="col-span-3 mb-col-12 relative mb-none">
    <div class="mt15"><?= $data['topic']['facet_description']; ?></div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>