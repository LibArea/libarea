<div class="br-gray bg-white mb15 relative">
  <?= Html::image($data['facet']['facet_img'], $data['facet']['facet_title'], 'img-2xl mt20 mb-mt5 mb-img-2xl profile-ava', 'logo', 'max'); ?>
  <div class="relative">
    <img class="blog-cover-img" src="<?= Html::coverUrl($data['facet']['facet_cover_art'], 'blog'); ?>" alt="<?= $data['facet']['facet_title']; ?>">
  </div>
  <div class="mt20">
    <div class="right m15 mb-mt5">
      <?= Html::signed([
        'type'            => 'facet',
        'id'              => $data['facet']['facet_id'],
        'content_user_id' => $data['facet']['facet_user_id'],
        'state'           => is_array($data['facet_signed']),
      ]); ?>
    </div>

    <h1 class="mb-block mb-text-xl m0 ml15">
      <?= $data['facet']['facet_seo_title']; ?>
      <?php if (UserData::checkAdmin() || $data['facet']['facet_user_id'] == UserData::getUserId()) : ?>
        <sup>
          <a class="ml5" href="<?= url('content.edit', ['type' => 'blog', 'id' => $data['facet']['facet_id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        </sup>
      <?php endif; ?>
    </h1>

    <div class="mt0 m15 mb-none">
      <?= $data['facet']['facet_short_description']; ?>
    </div>
  </div>
</div>