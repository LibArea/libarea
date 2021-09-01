<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15 space-tags">
        <?php if ($data['link']['link_title']) { ?>
          <div class="right heart-link">
            <?= votes($uid['user_id'], $data['link'], 'link'); ?>
          </div>
          <h1><?= $data['link']['link_title']; ?>
            <?php if ($uid['user_trust_level'] > 4) { ?>
              <a class="size-13 ml5" title="<?= lang('Edit'); ?>" href="/admin/webs/<?= $data['link']['link_id']; ?>/edit">
                <i class="icon-pencil size-15"></i>
              </a>
            <?php } ?>
          </h1>
          <div class="gray">
            <?= $data['link']['link_content']; ?>
          </div>
          <div class="domain-footer-small">
            <a class="green" rel="nofollow noreferrer ugc" href="<?= $data['link']['link_url']; ?>">
              <?= favicon_img($data['link']['link_id'], $data['link']['link_url_domain']); ?>
              <?= $data['link']['link_url']; ?>
            </a>

            <span class="right gray"><?= $data['link']['link_count']; ?></span>
          </div>
        <?php } else { ?>
          <h1><?= lang('Domain') . ': ' . $data['domain']; ?></h1>
        <?php } ?>
      </div>
    </div>

    <?php includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>

    <?= pagination($data['pNum'], $data['pagesCount'], null, '/domain/' . $data['link']['link_url_domain']); ?>

  </main>
  <aside>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15 space-tags">
        <?php if (!empty($data['domains'])) { ?>
          <div class="uppercase mb5 mt5 size-13"><?= lang('Domains'); ?></div>
          <?php foreach ($data['domains'] as  $domain) { ?>
            <a class="size-13 gray" href="/domain/<?= $domain['link_url_domain']; ?>">
              <i class="icon-link middle"></i> <?= $domain['link_url_domain']; ?>
              <sup class="size-13"><?= $domain['link_count']; ?></sup>
            </a><br>
          <?php } ?>
        <?php } else { ?>
          <p><?= lang('There are no domains'); ?>...</p>
        <?php } ?>
      </div>
    </div>
  </aside>
</div>