<main class="col-two">
  <div class="box-white">
    <?php if ($data['site']['item_title']) { ?>
      <div class="right mt10">
        <?= Html::votes($user['id'], $data['site'], 'item', 'ps', 'mr10'); ?>
      </div>
      <h1><?= $data['site']['item_title']; ?>
        <?php if ($user['trust_level'] > 4) { ?>
          <a class="text-sm ml5" title="<?= __('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $data['site']['item_id']]); ?>">
            <i class="bi-pencil"></i>
          </a>
        <?php } ?>
      </h1>
      <div class="gray">
        <?= Content::text(Html::fragment($data['site']['item_content']), 'line'); ?>
      </div>
      <div class="gray mt5 mb5">
        <a class="green" rel="nofollow noreferrer ugc" href="<?= $data['site']['item_url']; ?>">
          <?= Html::websiteImage($data['site']['item_id'], 'favicon', $data['site']['item_domain'], 'favicons'); ?>
          <?= $data['site']['item_url']; ?>
        </a>
        <span class="right gray-600"><i class="bi-journal mr5"></i> <?= $data['site']['item_count']; ?></span>
      </div>
    <?php } else { ?>
      <h1><?= __('domain') . ': ' . $data['domain']; ?></h1>
    <?php } ?>
  </div>

  <?= Tpl::insert('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], null, getUrlByName('domain', ['domain' => $data['site']['item_domain']])); ?>
</main>
<aside>
  <div class="sticky top-sm">
    <div class="box-white">
      <?= Tpl::insert('/_block/domains', ['data' => $data['domains']]); ?>
    </div>
  </div>
</aside>