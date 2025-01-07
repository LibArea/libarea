<div class="br-gray bg-white mb15 relative">
  <?= Img::image($data['facet']['facet_img'], $data['facet']['facet_title'], 'img-2xl mt20 mb-mt25 mb-img-2xl profile-ava', 'logo', 'max'); ?>
  <div class="relative hidden">
    <img class="box-cover-img" src="<?= Img::cover($data['facet']['facet_cover_art'], 'blog'); ?>" alt="<?= $data['facet']['facet_title']; ?>">
  </div>
  <div class="flex justify-between mt20">
    <h1 class="mb-block mb-text-xl mt20 ml15 mb-mt5">
      <?= $data['facet']['facet_seo_title']; ?>
      <?php if ($container->user()->admin() || $data['facet']['facet_user_id'] == $container->user()->id()) : ?>
        <sup>
          <a class="ml5" href="<?= url('facet.form.edit', ['type' => 'blog', 'id' => $data['facet']['facet_id']]); ?>">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        </sup>
      <?php endif; ?>
    </h1>

    <div class="m15 mb-mt5 gray-600">
      <?= Html::signed([
        'type'            => 'facet',
        'id'              => $data['facet']['facet_id'],
        'content_user_id' => $data['facet']['facet_user_id'],
        'state'           => is_array($data['facet_signed']),
      ]); ?>
    </div>
  </div>

    <div class="ml15 mb15 mb-none">
      <?= $data['facet']['facet_short_description']; ?>
    </div>
</div>