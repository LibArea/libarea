<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15 space-tags">
    <?php if ($uid['user_trust_level'] == 5) { ?>
      <a title="<?= lang('add'); ?>" class="right mt5" href="<?= getUrlByName('link-add'); ?>">
        <i class="icon-plus middle"></i>
      </a>
    <?php } ?>
    <h1 class="mb5"><?= lang('domains-title'); ?></h1>
    <?php if (!empty($data['domains'])) { ?>
      <?php foreach ($data['domains'] as  $domain) { ?>
        <a class="gray mr20" href="<?= getUrlByName('domain', ['domain' => $domain['link_url_domain']]); ?>">
          <?= $domain['link_url_domain']; ?>
          <sup class="size-14"><?= $domain['link_count']; ?></sup>
        </a>
      <?php } ?>
    <?php } else { ?>
      <p><?= lang('there are no domains'); ?>...</p>
    <?php } ?>
  </div>

  <?php if (!empty($data['links'])) { ?>
    <?php foreach ($data['links'] as $key => $link) { ?>
      <div class="bg-white br-rd-5 border-box-1 p20 mt15">
        <a href="<?= getUrlByName('domain', ['domain' => $link['link_url_domain']]); ?>">
          <h2 class="font-normal black size-24 mt0 mb0">
            <?php if ($link['link_title']) { ?>
              <?= $link['link_title']; ?>
            <?php } else { ?>
              Add title...
            <?php } ?>
          </h2>
        </a>
        <?php if ($uid['user_trust_level'] == 5) { ?>
          <a class="size-14 mr10 right" title="<?= lang('edit'); ?>" href="<?= getUrlByName('link-edit', ['id' => $link['link_id']]); ?>">
            <i class="icon-pencil size-15"></i>
          </a>
        <?php } ?>
        <span class="green">
          <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
          <?= $link['link_url_domain']; ?>
        </span>
        <div class="gray">
          <?php if ($link['link_content']) { ?>
            <?= $link['link_content']; ?>
          <?php } else { ?>
            Add content...
          <?php } ?>
        </div>
        <div class="pt10 hidden lowercase">
          <?= votes($uid['user_id'], $link, 'link'); ?>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
  <?php } ?>

  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/domains'); ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('domains-desc')]); ?>
</div>