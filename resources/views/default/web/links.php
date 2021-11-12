<main class="col-span-12 mb-col-12">
  <div class="pt5 mr15 pb5 ml15">
    <?php if ($uid['user_trust_level'] == 5) { ?>
      <a title="<?= Translate::get('add'); ?>" class="right mt5" href="/web/add">
        <i class="bi bi-plus-lg middle"></i>
      </a>
    <?php } ?>
    <h1 class="mt5 mb10 size-24 font-normal"><?= Translate::get('domains-title'); ?></h1>
    <div class="gray size-14 mb15"><?= Translate::get('under development'); ?>...</div>

    <div class="flex mb20">
      <?php foreach (Config::get('web-root-categories') as  $cat) { ?>
        <div class="mr60">
          <a class="pt5 pr10 mr60 underline-hover size-21 block " title="<?= $cat['title']; ?>" href="<?= getUrlByName('web.topic', ['slug' => $cat['url']]); ?>">
            <?= $cat['title']; ?>
          </a>
          <?php foreach ($cat['sub'] as $sub) { ?>
            <a class="pr10 pb5 size-14 black inline" title="<?= $sub['title']; ?>" href="<?= getUrlByName('web.topic', ['slug' => $sub['url']]); ?>">
              <?= $sub['title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>
    </div>

    <?php if (!empty($data['links'])) { ?>
      <?php foreach ($data['links'] as $key => $link) { ?>
        <?php if ($link['link_published'] == 1) { ?>
          <div class="pt20 pb5 flex flex-row gap-2">
            <div class="w200 no-mob">
              <?= thumbs_img($link['link_url_domain'], $link['link_title'], 'mr5 mt5 w200 box-shadow'); ?>
            </div>
            <div class="w-100">
              <a class="pt0 pr5 size-21" href="<?= getUrlByName('web.website', ['slug' => $link['link_url_domain']]); ?>">
                <h2 class="font-normal inline underline-hover size-21 mt0 mb0">
                  <?= $link['link_title']; ?>
                </h2>
              </a>
              <?php if ($uid['user_trust_level'] == 5) { ?>
                <a class="ml15" title="<?= Translate::get('edit'); ?>" href="/web/edit/<?= $link['link_id']; ?>">
                  <i class="bi bi-pencil size-15"></i>
                </a>
              <?php } ?>
              <div class="size-15 mt5 mb15 max-w780">
                <?= $link['link_content']; ?>
              </div>
              <div class="flex flex-row gap-2 items-center max-w780">
                <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
                <div class="green size-14 mr20">
                  <?= $link['link_url_domain']; ?>
                  <div class="pt5 lowercase">
                    <?= html_topic($link['topic_list'], 'web.topic', 'gray-light mr15'); ?>
                  </div>
                </div>
                <div class="hidden lowercase ml-auto">
                  <?= votes($uid['user_id'], $link, 'link', 'mr5'); ?>
                </div>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <?php if ($uid['user_trust_level'] == 5) { ?>
            <div class="mt15 mb15">
              <i class="bi bi-link-45deg red mr5 size-18"></i>
              <?= $link['link_title']; ?> (<?= $link['link_url_domain']; ?>)
              <a class="ml15" title="<?= Translate::get('edit'); ?>" href="/web/edit/<?= $link['link_id']; ?>">
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
  <div class="pl10">
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('web')); ?>
  </div>
</main>
<?= includeTemplate('/_block/wide-footer'); ?>