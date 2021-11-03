<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="bg-white br-rd5 br-box-grey pt5 pr15 pb5 pl15">
    <?php if ($uid['user_trust_level'] == 5) { ?>
      <a title="<?= Translate::get('add'); ?>" class="right mt5" href="/web/add">
        <i class="bi bi-plus-lg middle"></i>
      </a>
    <?php } ?>
    <h1 class="mt5 mb10 size-24 font-normal"><?= Translate::get('domains-title'); ?></h1>
    <div class="gray size-14 mb5"><?= Translate::get('under development'); ?>...</div>

    <?php if (!empty($data['links'])) { ?>
      <?php foreach ($data['links'] as $key => $link) { ?>
        <?php if ($link['link_published'] == 1) { ?>
    
        <div class="pt20 pb5 flex flex-row gap-2">
          <div class="mr20 w200 no-mob">
            <?= thumbs_img($link['link_url_domain'], $link['link_title']); ?>
          </div>
          <div class="mr20 flex-auto">
            <a href="<?= getUrlByName('domain', ['domain' => $link['link_url_domain']]); ?>">
              <h2 class="font-normal size-21 mt0 mb0">
                <?= $link['link_title']; ?>
                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a class="ml15" title="<?= Translate::get('edit'); ?>" href="/web/edit/<?=  $link['link_id']; ?>">
                    <i class="bi bi-pencil size-15"></i>
                  </a>
                <?php } ?>
              </h2>
            </a>
            <div class="size-15 mt5 max-w780">
                <?= $link['link_content']; ?>
            </div>
            <div class="flex flex-row gap-2 items-center mt20">
              <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
              <div class="green size-14 mr20">
                <?= $link['link_url_domain']; ?>
                <div class="pt5 lowercase">
                  <?= html_topic($link['topic_list'], 'web.topic', 'gray-light mr15'); ?>
                </div>
              </div>
              <div class="hidden lowercase ml-auto">
                <?= votes($uid['user_id'], $link, 'link'); ?>
              </div>
            </div>
          </div>
        </div>
         <?php } else { ?>
          <?php if ($uid['user_trust_level'] == 5) { ?>
               <div class="mt15 mb15">
                <i class="bi bi-link-45deg red mr5 size-18"></i>
                <?= $link['link_title']; ?> (<?= $link['link_url_domain']; ?>)
                <a class="ml15" title="<?= Translate::get('edit'); ?>" href="/web/edit/<?=  $link['link_id']; ?>">
                  <i class="bi bi-pencil size-15"></i>
                </a>
              </div>
            <?php } ?>
         <?php } ?>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/domains'); ?>
</main>
<?= includeTemplate('/_block/wide-footer'); ?>