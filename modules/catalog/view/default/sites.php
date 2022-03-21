<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<div class="item-cat">
  <?= $data['breadcrumb']; ?>

  <h1>
    <?= $data['category']['facet_title']; ?>
    <?php if (UserData::checkAdmin()) { ?>
      <a class="text-sm ml5" href="<?= getUrlByName('content.edit', ['type' => 'category', 'id' => $data['category']['facet_id']]); ?>">
        <sup><i class="bi-pencil gray"></i></sup>
      </a>
      <a class="text-sm ml15" href="<?= getUrlByName('admin.category.structure'); ?>">
        <sup class="gray-600"><i class="bi-columns-gap mr5"></i></sup>
      </a>
      <small class="text-sm gray-600"><sup><?= $data['category']['facet_type']; ?></sup></small>
    <?php } ?>
  </h1>
</div>

<?php if ($data['childrens']) { ?>
  <div class="item-categories">
    <?php foreach ($data['childrens'] as $lt) { ?>
      <div>
        <a class="text-2xl" href="<?= getUrlByName('web.dir', ['cat' => $data['screening'], 'slug' => $lt['facet_slug']]); ?>">
          <?= $lt['facet_title']; ?>
        </a> <sup class="gray-600"><?= $lt['counts']; ?></sup>
        <?php if (UserData::checkAdmin()) { ?>
          <a class="ml5" href="<?= getUrlByName('content.edit', ['type' => 'category', 'id' => $lt['facet_id']]); ?>">
            <sup><i class="bi-pencil"></i>
          </a>
          <small class="text-sm gray-600"><sup><?= $lt['facet_type']; ?></sup></small>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
<?php } ?>
<?php if ($data['low_matching']) { ?>
  <div class="item-categories mb-block">
    <?php foreach ($data['low_matching'] as $rl) { ?>
      <div class="inline mr20">
        <a class="text-2xl" href="<?= getUrlByName('web.dir', ['cat' => $data['screening'], 'slug' => $rl['facet_slug']]); ?>">
          @<?= $rl['facet_title']; ?>
        </a>
        <?php if (UserData::checkAdmin()) { ?>
          <a class="text-sm ml5" href="<?= getUrlByName('category.edit', ['id' => $rl['facet_id']]); ?>">
            <sup class="gray-600"><i class="bi-pencil"></i> <small><?= $rl['facet_type']; ?></small></sup>
          </a>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<div id="contentWrapper">
  <main>
    <?= includeTemplate('/view/default/nav', ['data' => $data, 'uid' => $user['id']]); ?>

    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user, 'screening' => $data['screening']]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/web/' . $data['screening']); ?>
  </main>
  <aside>
    <div class="box-yellow mt15 text-sm"><?= Content::text($data['category']['facet_info'], 'line'); ?></div>
    <?php if (UserData::checkActiveUser()) { ?>
      <div class="box-white text-sm bg-violet-50 mt15">
        <h3 class="uppercase-box"><?= Translate::get('menu'); ?></h3>
        <ul class="menu">
          <?= includeTemplate('/view/default/_block/add-site', ['user' => $user, 'data' => $data]); ?>

          <?= tabs_nav(
            'menu',
            $data['sheet'],
            $user,
            $pages = Config::get('catalog/menu.user')
          ); ?>
        </ul>
      </div>
    <?php } ?>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>