<div class="relative items-center flex mb15">
  <img class="w-100" src="<?= Html::coverUrl($data['facet']['facet_cover_art'], 'blog'); ?>">
  <div class="blog-flex-telo">
    <div class="blog-flex-box">
      <div class="flex">
        <?= Html::image($data['facet']['facet_img'], $data['facet']['facet_title'], 'img-xl mr15', 'logo', 'max'); ?>
        <div>
          <h1 class="m0 text-2xl">
            <?= $data['facet']['facet_seo_title']; ?>
          </h1>
          <div class="text-sm mb-none mt15"><?= $data['facet']['facet_short_description']; ?></div>
        </div>
      </div>

      <div class="right white">
        <?php if (UserData::checkAdmin() || $data['facet']['facet_user_id'] == UserData::getUserId()) : ?>
          <a class="white right" href="<?= url('content.edit', ['type' => 'blog', 'id' => $data['facet']['facet_id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        <?php endif; ?>
        <br>
        <div class="mt15">
          <?= Html::signed([
            'type'            => 'facet',
            'id'              => $data['facet']['facet_id'],
            'content_user_id' => $data['facet']['facet_user_id'],
            'state'           => is_array($data['facet_signed']),
          ]); ?>
        </div>
      </div>
    </div>
  </div>
</div>