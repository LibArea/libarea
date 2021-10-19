<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <?php if ($uid['user_trust_level'] == 5) { ?>
      <a title="<?= lang('add'); ?>" class="right mt5" href="<?= getUrlByName('link-add'); ?>">
        <i class="bi bi-plus-lg middle"></i>
      </a>
    <?php } ?>
    <h1 class="mb5"><?= lang('domains-title'); ?></h1>
    <div class="gray size-14 mb5"><?= lang('under development'); ?>...</div>

    <?php if (!empty($data['links'])) { ?>
      <?php foreach ($data['links'] as $key => $link) { ?>
        <div class="pt20 pb5 flex flex-row gap-2">
          <div class="mr20 w200 no-mob">
            <?= thumbs_img($link['link_url_domain'], $link['link_title']); ?>
          </div>
          <div class="mr20 flex-auto">
            <?php if ($uid['user_trust_level'] == 5) { ?>
              <a class="size-14 mr10 right" title="<?= lang('edit'); ?>" href="<?= getUrlByName('link-edit', ['id' => $link['link_id']]); ?>">
                <i class="bi bi-pencil size-15"></i>
              </a>
            <?php } ?>
            <a href="<?= getUrlByName('domain', ['domain' => $link['link_url_domain']]); ?>">
              <h2 class="font-normal size-21 mt0 mb0">
                <?php if ($link['link_title']) { ?>
                  <?= $link['link_title']; ?>
                <?php } else { ?>
                  Add title...
                <?php } ?>
              </h2>
            </a>
            <div class="size-15 mt5 max-w780">
              <?php if ($link['link_content']) { ?>
                <?= $link['link_content']; ?>
              <?php } else { ?>
                Add content...
              <?php } ?>
            </div>
            <div class="flex flex-row gap-2 mt10 items-center">
              <div class="w18">
                <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
              </div>
              <div class="green size-14 mr20">
                <?= $link['link_url_domain']; ?>
                <div class="pt5 lowercase">
                  <?= html_topic($link['topic_list'], 'web.topic', 'gray-light mr15'); ?>
                </div>
              </div>
              <div class="pt10 hidden lowercase ml-auto">
                <?= votes($uid['user_id'], $link, 'link'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/domains'); ?>
</main>
<?= includeTemplate('/_block/wide-footer'); ?>