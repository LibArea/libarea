<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="white-box pt5 pr15 pb5 pl15">
    <a class="right" title="<?= lang('add'); ?>" href="<?= getUrlByName('link-add'); ?>">
      <i class="bi bi-plus-lg middle"></i>
    </a>
    <?= breadcrumb('/admin', lang('admin'), null, null, lang('domains')); ?>

    <div class="domains mt20">
      <?php if (!empty($data['domains'])) { ?>
        <?php foreach ($data['domains'] as $key => $link) { ?>
          <div class="domain-box">
            <span class="add-favicon right size-13" data-id="<?= $link['link_id']; ?>">
              + favicon
            </span>
            <div class="size-21">
              <?php if ($link['link_title']) { ?>
                <?= $link['link_title']; ?>
              <?php } else { ?>
                Add title...
              <?php } ?>
            </div>
            <div class="content-telo">
              <?php if ($link['link_content']) { ?>
                <?= $link['link_content']; ?>
              <?php } else { ?>
                Add content...
              <?php } ?>
            </div>

            <div class="border-bottom mb15 mt5 pb5 size-13 hidden gray">
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
              <a href="<?= getUrlByName('link-edit', ['id' => $link['link_id']]); ?>">
                <?= lang('edit'); ?>
              </a>
              <span class="right heart-link red">
                +<?= $link['link_count']; ?>
              </span>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
      <?php } ?>
    </div>
  </div>
</main>