<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
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

<div class="box-white">
  <?php if (!empty($data['domains'])) { ?>
    <?php foreach ($data['domains'] as $key => $item) { ?>
      <div class="domain-box">
        <span class="add-favicon right text-sm" data-id="<?= $item['item_id']; ?>">
          + favicon
        </span>
        <div class="text-2xl">
          <a href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
            <?= $item['item_title_url']; ?>
          </a>
        </div>
        <?= html_facet($item['facet_list'], 'topic', 'web.topic', 'gray-600 text-sm mr10'); ?>
        <div class="max-w780">
          <?= $item['item_content_url']; ?>
        </div>
        <div class="br-bottom mb15 pb10 text-sm hidden gray">
          <span class="inline">
            <?= votes($user['id'], $item, 'item', 'ps', 'mr5'); ?>
          </span>
          <a class="green-600" rel="nofollow noreferrer" href="<?= $item['item_url']; ?>">
            <span class="green-600"><?= $item['item_url']; ?></span>
          </a> &#183; 
           id<?= $item['item_id']; ?> &#183;
          <?= $item['item_url_domain']; ?> &#183;
          <?php if ($item['item_is_deleted'] == 0) { ?>
            active
          <?php } else { ?>
            <span class="red-500">Ban</span>
          <?php } ?>
          &#183;
          <a href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
            <?= Translate::get('edit'); ?>
          </a>
          <span class="right">
            <?= website_img($item['item_url_domain'], 'favicon', $item['item_url_domain'], 'favicons'); ?>
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

  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.webs')); ?>
</div>

</main>
<?= includeTemplate('/view/default/footer'); ?>