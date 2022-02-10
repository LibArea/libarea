<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<div class="ml30 mb15">

  <a href="<?= getUrlByName('web'); ?>" class="text-sm gray-400"><?= Translate::get('websites'); ?></a>

  <?php if (!empty($data['high_topics'])) {
    $site = $data['high_topics']; ?>
    <span class="gray mr5 ml5">/</span>
    <a href="/web/<?= $site['facet_slug']; ?>">
      <?= $site['facet_title']; ?>
    </a>
  <?php } ?>
  
  <h1>
    <?= $data['category']['facet_title']; ?>
    <?php if (UserData::checkAdmin()) { ?>
      <a class="text-sm ml5" href="<?= getUrlByName('category.edit', ['id' => $data['category']['facet_id']]); ?>">
        <sup><i class="bi bi-pencil gray"></i></sup>
      </a>
      <a class="text-sm ml15" href="<?= getUrlByName('topic', ['slug' => $data['category']['facet_slug']]); ?>">
        <sup class="gray-400"><i class="bi bi-columns-gap"></i> <small><?= $data['category']['facet_type']; ?></small></sup>
      </a>
    <?php } ?>
  </h1>
</div>

  <?php if ($data['childrens']) { ?>
    <div class="item-categories mb-block">
    <?php foreach ($data['childrens'] as $lt) { ?>
        <div>
          <a class="text-2xl" href="/web/<?= $lt['facet_slug']; ?>">
            <?= $lt['facet_title']; ?>
          </a> <sup class="gray-400"><?= $lt['count']; ?></sup>
          <?php if (UserData::checkAdmin()) { ?>
            <a class="ml5" href="<?= getUrlByName('category.edit', ['id' => $lt['facet_id']]); ?>">
              <sup class="gray-400"><i class="bi bi-pencil"></i> <small><?= $lt['facet_type']; ?></small></sup>
            </a>
          <?php } ?>
        </div>
     <?php } ?>
     </div>
  <?php } ?>
  <?php if ($data['low_matching']) { ?>
   <div class="item-categories mb-block">
    <?php foreach ($data['low_matching'] as $rl) { ?>
        <div class="inline mr20">
          <a class="text-2xl" href="<?= getUrlByName('web.dir', ['slug' => $rl['facet_slug']]); ?>">
            @<?= $rl['facet_title']; ?>
          </a>
          <?php if (UserData::checkAdmin()) { ?>
            <a class="text-sm ml5" href="<?= getUrlByName('category.edit', ['id' => $rl['facet_id']]); ?>">
              <sup class="gray-400"><i class="bi bi-pencil"></i> <small><?= $rl['facet_type']; ?></small></sup>
            </a>
          <?php } ?>
        </div>
    <?php } ?>
    </div>
  <?php } ?>
  
 

<div class="grid grid-cols-12 gap-4">
  <main class="col-span-9 mb-col-12 ml30">
    <?= includeTemplate('/view/default/nav', ['data' => $data, 'uid' => $user['id']]); ?>

    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('web')); ?>
  </main>
  <aside class="col-span-3 mb-col-12 relative mb-none">
    <div class="box-yellow mt15 text-sm"><?= $data['category']['facet_description']; ?></div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>