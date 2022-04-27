<main>
  <div class="box">
    <?php if ($data['site']['item_title']) : ?>
      <div class="right mt15">
        <?= Html::votes($data['site'], 'item', 'ps', 'bi-heart mr10'); ?>
      </div>
      <h1><?= $data['site']['item_title']; ?>
        <?php if (UserData::checkAdmin()) : ?>
          <a class="text-sm ml5" title="<?= __('edit'); ?>" href="<?= url('web.edit', ['id' => $data['site']['item_id']]); ?>">
            <i class="bi-pencil"></i>
          </a>
        <?php endif; ?>
      </h1>
      <div class="gray">
        <?= Html::fragment(Content::text($data['site']['item_content'], 'line'), 200); ?>
      </div>
      <div class="gray mt5 mb5">
        <a class="green" rel="nofollow noreferrer ugc" href="<?= $data['site']['item_url']; ?>">
          <?= Html::websiteImage($data['site']['item_id'], 'favicon', $data['site']['item_domain'], 'favicons'); ?>
          <?= $data['site']['item_url']; ?>
        </a>
        <span class="right gray-600"><i class="bi-journal mr5"></i> <?= $data['site']['item_count']; ?></span>
      </div>
    <?php else : ?>
      <h1><?= __('domain') . ': ' . $data['domain']; ?></h1>
    <?php endif; ?>
  </div>

  <?= Tpl::insert('/content/post/post', ['data' => $data]); ?>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], null, url('domain', ['domain' => $data['site']['item_domain']])); ?>
</main>
<aside>
  <div class="sticky top-sm">
    <div class="box">
      <?= Tpl::insert('/_block/domains', ['data' => $data['domains']]); ?>
    </div>
  </div>
</aside>