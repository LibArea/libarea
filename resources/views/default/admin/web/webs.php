<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/admin', ['sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <a class="right mr15" title="<?= Translate::get('add'); ?>" href="/web/add">
    <i class="bi bi-plus-lg middle"></i>
  </a>

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('domains')
  ); ?>

  <div class="bg-white br-box-gray p15">
    <?php if (!empty($data['domains'])) { ?>
      <?php foreach ($data['domains'] as $key => $link) { ?>
        <div class="domain-box">
          <span class="add-favicon right size-13" data-id="<?= $link['link_id']; ?>">
            + favicon
          </span>
          <div class="size-21">
            <?= $link['link_title']; ?>
          </div>
          <?= html_topic($link['topic_list'], 'web.topic', 'gray-light size-14 mr10'); ?>
          <div class="content-telo">
            <?= $link['link_content']; ?>
          </div>
          <div class="br-bottom mb15 mt5 pb5 size-13 hidden gray">
            <a class="green" rel="nofollow noreferrer" href="<?= $link['link_url']; ?>">
              <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
              <span class="green"><?= $link['link_url']; ?></span>
            </a> |
            id<?= $link['link_id']; ?>
            <span class="mr5 ml5"> &#183; </span>
            <?= $link['link_url_domain']; ?>
            <span class="mr5 ml5"> &#183; </span>
            <?php if ($link['link_is_deleted'] == 0) { ?>
              active
            <?php } else { ?>
              <span class="red">Ban</span>
            <?php } ?>
            <span class="mr5 ml5"> &#183; </span>
            <a href="/web/edit/<?= $link['link_id']; ?>">
              <?= Translate::get('edit'); ?>
            </a>
            <span class="right heart-link red">
              +<?= $link['link_count']; ?>
            </span>
            <?php if ($link['link_published'] == 0) { ?>
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