<div class="col-span-2 justify-between no-mob">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7">
  <div class="bg-white br-rd5 br-box-gray mb15 pt5 pr15 pb5 pl15">
    <?php if ($data['item']['item_title_url']) { ?>
      <div class="right mt5">
        <?= votes($uid['user_id'], $data['item'], 'item', 'ps', 'mr5'); ?>
      </div>
      <h1 class="mt5 mb10 text-2xl font-normal"><?= $data['item']['item_title_url']; ?>
        <?php if ($uid['user_trust_level'] > 4) { ?>
          <a class="text-sm ml5" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $data['item']['item_id']]); ?>">
            <i class="bi bi-pencil"></i>
          </a>
        <?php } ?>
      </h1>
      <div class="gray">
        <?= $data['item']['item_content_url']; ?>
      </div>
      <div class="gray mt5 mb5">
        <a class="green-600" rel="nofollow noreferrer ugc" href="<?= $data['item']['item_url']; ?>">
          <?= website_img($data['item']['item_id'], 'favicon', $data['item']['item_url_domain'], 'mr5 w18 h18'); ?>
          <?= $data['item']['item_url']; ?>
        </a>
        <span class="right"><?= $data['item']['item_count']; ?></span>
      </div>
    <?php } else { ?>
      <h1><?= Translate::get('domain') . ': ' . $data['domain']; ?></h1>
    <?php } ?>
  </div>

  <?= import('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <?= pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('domain', ['domain' => $data['item']['item_url_domain']])); ?>
</main>
<aside class="col-span-3 relative">
  <div class="sticky top60">
    <div class="bg-white br-rd5 br-box-gray pt5 pr15 pb10 pl15">
      <?= import('/_block/domains', ['data' => $data['domains'], 'uid' => $uid]); ?>
    </div>
  </div>
</aside>