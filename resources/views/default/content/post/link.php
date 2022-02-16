<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>
  </nav>
</div>

<main class="col-span-7">
  <div class="bg-white br-rd5 br-box-gray mb15 p15">
    <?php if ($data['site']['item_title_url']) { ?>
      <div class="right mt15">
        <?= votes($user['id'], $data['site'], 'item', 'ps', 'mr10'); ?>
      </div>
      <h1><?= $data['site']['item_title_url']; ?>
        <?php if ($user['trust_level'] > 4) { ?>
          <a class="text-sm ml5" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $data['site']['item_id']]); ?>">
            <i class="bi bi-pencil"></i>
          </a>
        <?php } ?>
      </h1>
      <div class="gray">
        <?= $data['site']['item_content_url']; ?>
      </div>
      <div class="gray mt5 mb5">
        <a class="green-600" rel="nofollow noreferrer ugc" href="<?= $data['site']['item_url']; ?>">
          <?= website_img($data['site']['item_id'], 'favicon', $data['site']['item_url_domain'], 'favicons'); ?>
          <?= $data['site']['item_url']; ?>
        </a>
        <span class="right gray-400"><i class="bi bi-journal mr5"></i> <?= $data['site']['item_count']; ?></span>
      </div>
    <?php } else { ?>
      <h1><?= Translate::get('domain') . ': ' . $data['domain']; ?></h1>
    <?php } ?>
  </div>

  <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  <?= pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('domain', ['domain' => $data['site']['item_url_domain']])); ?>
</main>
<aside class="col-span-3 relative">
  <div class="sticky top-sm">
    <div class="bg-white br-rd5 br-box-gray p15">
      <?= Tpl::import('/_block/domains', ['data' => $data['domains']]); ?>
    </div>
  </div>
</aside>