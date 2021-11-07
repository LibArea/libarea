<main class="col-span-9 mb-col-12">
  <div class="bg-white br-rd5 br-box-grey pt5 pr15 pb5 pl15">
    <?php if ($uid['user_trust_level'] == 5) { ?>
      <a title="<?= Translate::get('add'); ?>" class="right mt5" href="/web/add">
        <i class="bi bi-plus-lg middle"></i>
      </a>
    <?php } ?>
    <a href="/web" class="size-14">← <?= Translate::get('sites'); ?></a>
    <h1 class="mt0 mb5 size-24">
      <?= $data['topic']['topic_title']; ?>
      <?php if ($uid['user_trust_level'] == 5) { ?>
        <a class="size-14" href="/topic/edit/<?= $data['topic']['topic_id']; ?>">
          <sup><i class="bi bi-pencil gray"></i></sup>
        </a>
      <?php } ?>
    </h1>

    <?php if ($data['high_topics']) { ?>
      <div class="flex mb20">
        <?php foreach ($data['high_topics'] as $ht) { ?>
          <?php if ($ht['topic_is_web'] == 1) { ?>
            <a class="inline mr20" href="/web/<?= $ht['topic_slug']; ?>"><?= $ht['topic_title']; ?></a>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>

    <?php if ($data['low_topics']) { ?>
      <div class="mb20">
        <?php foreach ($data['low_topics'] as $lt) { ?>
          <?php if ($lt['topic_is_web'] == 1) { ?>
            <a class="inline mr20" href="/web/<?= $lt['topic_slug']; ?>"><?= $lt['topic_title']; ?></a>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>

    <?php if ($data['topic_related']) { ?>
      <div class=" mb20">
        <div class="gray"><?= Translate::get('see also'); ?></div>
        <?php foreach ($data['topic_related'] as $rl) { ?>
          <?php if ($rl['topic_is_web'] == 1) { ?>
            <a class="inline mr20 size-14 black" href="/web/<?= $rl['topic_slug']; ?>">
              <?= $rl['topic_title']; ?>
            </a>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>

    <?php if (!empty($data['links'])) { ?>
      <?php foreach ($data['links'] as $key => $link) { ?>
        <div class="pt20 pb5 flex flex-row gap-2">
          <div class="mr20 w200 no-mob">
            <?= thumbs_img($link['link_url_domain'], $link['link_title']); ?>
          </div>
          <div class="mr20 flex-auto">
            <a href="<?= getUrlByName('domain', ['domain' => $link['link_url_domain']]); ?>">
              <h2 class="font-normal size-21 mt0 mb0">
                <?= $link['link_title']; ?>
                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a class="ml15" title="<?= Translate::get('edit'); ?>" href="/web/edit/<?= $link['link_id']; ?>">
                    <i class="bi bi-pencil size-15"></i>
                  </a>
                <?php } ?>
              </h2>
            </a>
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
                <?= votes($uid['user_id'], $link, 'link'); ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/domains'); ?>
</main>
<aside class="col-span-3 relative br-rd5 no-mob">
  <div class="bg-white p15  br-box-grey">
    <?= $data['topic']['topic_description']; ?>
  </div>
</aside>
<?= includeTemplate('/_block/wide-footer'); ?>