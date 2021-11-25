<main class="col-span-9">
  <div class="bg-white br-rd5 br-box-gray mb15 pt5 pr15 pb15 pl15">

    <?= breadcrumb(
      getUrlByName('web'),
      Translate::get('sites'),
      null,
      null,
      Translate::get('website')
    ); ?>

    <div class="right heart-link mt5">
      <?= votes($uid['user_id'], $data['link'], 'link', 'size-21', 'block'); ?>
    </div>

    <h1 class="mt5 mb10 size-24 font-normal"><?= $data['link']['link_title']; ?>
      <?php if ($uid['user_trust_level'] > 4) { ?>
        <a class="size-14 ml5" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $data['link']['link_id']]); ?>">
          <i class="bi bi-pencil size-15"></i>
        </a>
      <?php } ?>
    </h1>

    <div class="flex">
      <?= thumbs_img($data['link']['link_url_domain'], $data['link']['link_title'], 'mr5 mt5 w400 box-shadow'); ?>
      <div class="ml60">
        <?= $data['link']['link_content']; ?>

        <div class="gray mt20 mb5">
          <a class="green" rel="nofollow noreferrer ugc" href="<?= $data['link']['link_url']; ?>">
            <?= favicon_img($data['link']['link_id'], $data['link']['link_url_domain']); ?>
            <?= $data['link']['link_url']; ?>
          </a>
        </div>

        <?php if (!empty($data['topics'])) { ?>
          <div class="mt20 mb20 lowercase">
            <?php foreach ($data['topics'] as $topic) { ?>
              <?php if ($topic['facet_is_web'] == 1) { ?>
                <a class="pt5 pr20 pb5 blue block size-18" href="<?= getUrlByName('web.topic', ['slug' => $topic['facet_slug']]); ?>">
                  <?= $topic['facet_title']; ?>
                </a>
              <?php } ?>
            <?php } ?>
          </div>
        <?php } ?>

      </div>
    </div>
  </div>
</main>
<aside class="col-span-3 relative">
  <div class="bg-white br-rd5 br-box-gray pt5 pr15 pb10 pl15">
    <?php if ($data['high_leve']) { ?>
      <div class="gray"><?= Translate::get('see also'); ?></div>
      <?php foreach ($data['high_leve'] as $rl) { ?>
        <?php if ($rl['facet_is_web'] == 1) { ?>
          <a class="inline mr20 size-14 black" href="<?= getUrlByName('web.topic', ['slug' => $rl['facet_slug']]); ?>">
            <?= $rl['facet_title']; ?>
          </a>
        <?php } ?>
      <?php } ?>
    <?php } else { ?>
      ....
    <?php } ?>
  </div>
</aside>