<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7">
  <div class="bg-white br-rd5 border-box-1 mb15 pt5 pr15 pb5 pl15">
    <?php if ($data['link']['link_title']) { ?>
      <div class="right heart-link mt5">
        <?= votes($uid['user_id'], $data['link'], 'link'); ?>
      </div>
      <h1 class="mt5 mb10 size-24 font-normal"><?= $data['link']['link_title']; ?>
        <?php if ($uid['user_trust_level'] > 4) { ?>
          <a class="size-14 ml5" title="<?= Translate::get('edit'); ?>" href="/web/edit/<?= $data['link']['link_id']; ?>">
            <i class="bi bi-pencil size-15"></i>
          </a>
        <?php } ?>
      </h1>
      <div class="gray">
        <?= $data['link']['link_content']; ?>
      </div>
      <div class="gray mt5 mb5">
        <a class="green" rel="nofollow noreferrer ugc" href="<?= $data['link']['link_url']; ?>">
          <?= favicon_img($data['link']['link_id'], $data['link']['link_url_domain']); ?>
          <?= $data['link']['link_url']; ?>
        </a>
        <span class="right"><?= $data['link']['link_count']; ?></span>
      </div>
    <?php } else { ?>
      <h1><?= Translate::get('domain') . ': ' . $data['domain']; ?></h1>
    <?php } ?>
  </div>

  <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <?= pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('domain', ['domain' => $data['link']['link_url_domain']])); ?>
</main>
<aside class="col-span-3 relative">
  <div class="bg-white br-rd5 border-box-1 pt5 pr15 pb10 pl15">
    <?= includeTemplate('/_block/domains', ['data' => $data['domains']]); ?>
  </div>
</aside>