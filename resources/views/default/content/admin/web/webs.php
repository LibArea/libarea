<div class="sticky mt5 top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.admin'),
      ); ?>
</div>

<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'pages'   => [
      [
        'id' => $data['type'] . '.all',
        'url' => getUrlByName('admin.' . $data['type']),
        'name' => Translate::get('all'),
        'icon' => 'bi bi-record-circle'
      ], [
        'id' => 'add',
        'url' => getUrlByName($data['type'] . '.add'),
        'name' => Translate::get('add'),
        'icon' => 'bi bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <?php if (!empty($data['domains'])) { ?>
    <?php foreach ($data['domains'] as $key => $item) { ?>
      <div class="domain-box">
        <span class="add-favicon right size-13" data-id="<?= $item['item_id']; ?>">
          + favicon
        </span>
        <div class="size-21">
          <a href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
            <?= $item['item_title_url']; ?>
          </a>
        </div>
        <?= html_topic($item['facet_list'], 'web.topic', 'gray-600 size-14 mr10'); ?>
        <div class="max-w780">
          <?= $item['item_content_url']; ?>
        </div>
        <div class="br-bottom mb15 mt5 pb10 size-13 hidden gray">
          <span class="inline mr5">
            <?= votes($uid['user_id'], $item, 'item', 'mr5'); ?>
          </span>
          <a class="green-600" rel="nofollow noreferrer" href="<?= $item['item_url']; ?>">
            <span class="green-600"><?= $item['item_url']; ?></span>
          </a> |
          id<?= $item['item_id']; ?>
          <span class="mr5 ml5"> &#183; </span>
          <?= $item['item_url_domain']; ?>
          <span class="mr5 ml5"> &#183; </span>
          <?php if ($item['item_is_deleted'] == 0) { ?>
            active
          <?php } else { ?>
            <span class="red-500">Ban</span>
          <?php } ?>
          <span class="mr5 ml5"> &#183; </span>
          <a href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
            <?= Translate::get('edit'); ?>
          </a>
          <span class="right mr5">
            <?= favicon_img($item['item_id'], $item['item_url_domain']); ?>
          </span>
          <?php if ($item['item_published'] == 0) { ?>
            <span class="ml15 red-500"> <?= Translate::get('posted'); ?> (<?= Translate::get('no'); ?>) </span>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>

</main>