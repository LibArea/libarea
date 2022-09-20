<main>
  <div class="box box-fon">
    <?php if ($data['site']['item_title']) : ?>
      <div class="right mt15">
        <?= Html::votes($data['site'], 'item'); ?>
      </div>
      <h1 class="text-2xl gray mb-text-xl m0"><?= $data['site']['item_title']; ?>
        <?php if (UserData::checkAdmin()) : ?>
          <a class="text-sm ml5" title="<?= __('app.edit'); ?>" href="<?= url('web.edit', ['id' => $data['site']['item_id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        <?php endif; ?>
      </h1>
      <div class="gray">
        <?= fragment($data['site']['item_content'], 200); ?>
      </div>
      <div class="gray mt5 mb5">
        <a class="green" rel="nofollow noreferrer ugc" href="<?= $data['site']['item_url']; ?>">
          <?= Img::website($data['site']['item_id'], 'favicon', $data['site']['item_domain'], 'favicons'); ?>
          <?= $data['site']['item_url']; ?>
        </a>
        <span class="right gray-600"><svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#post"></use>
          </svg> <?= $data['site']['item_count']; ?></span>
      </div>
    <?php else : ?>
      <h1><?= __('app.domain') . ': ' . $data['domain']; ?></h1>
    <?php endif; ?>
  </div>

  <?= insert('/content/post/post-card', ['data' => $data]); ?>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], null, url('domain', ['domain' => $data['site']['item_domain']])); ?>
</main>
<aside>
  <div class="sticky top-sm">
    <div class="box">
      <?= insert('/_block/domains', ['data' => $data['domains']]); ?>
    </div>
  </div>
</aside>