<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'user_id' => $uid['user_id'],
    'add'     => true,
    'pages'   => false
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
          <?= $item['item_title_url']; ?>
        </div>
        <?= html_topic($item['facet_list'], 'web.topic', 'gray-light size-14 mr10'); ?>
        <div class="max-w780">
          <?= $item['item_content_url']; ?>
        </div>
        <div class="br-bottom mb15 mt5 pb5 size-13 hidden gray">
          <a class="green" rel="nofollow noreferrer" href="<?= $item['item_url']; ?>">
            <?= favicon_img($item['item_id'], $item['item_url_domain']); ?>
            <span class="green"><?= $item['item_url']; ?></span>
          </a> |
          id<?= $item['item_id']; ?>
          <span class="mr5 ml5"> &#183; </span>
          <?= $item['item_url_domain']; ?>
          <span class="mr5 ml5"> &#183; </span>
          <?php if ($item['item_is_deleted'] == 0) { ?>
            active
          <?php } else { ?>
            <span class="red">Ban</span>
          <?php } ?>
          <span class="mr5 ml5"> &#183; </span>
          <a href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
            <?= Translate::get('edit'); ?>
          </a>
          <span class="right red">
            +<?= $item['item_count']; ?>
          </span>
          <?php if ($item['item_published'] == 0) { ?>
            <span class="ml15 red"> <?= Translate::get('posted'); ?> (<?= Translate::get('no'); ?>) </span>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>

</main>