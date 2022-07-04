<style nonce="<?= $_SERVER['nonce']; ?>">
  .bg-blog {
    background-image: linear-gradient(to right, white 0%, transparent 60%), url(<?= Html::coverUrl($data['facet']['facet_cover_art'], 'blog'); ?>);
    background-position: 50% 50%;
  }
</style>

<div class="box-flex bg-blog">
  <?= Html::image($data['facet']['facet_img'], $data['facet']['facet_title'], 'img-xl mr15', 'logo', 'max'); ?>
  <div class="flex-auto">
    <h1 class="mt10 text-2xl">
      <?php if (UserData::checkAdmin() || $data['facet']['facet_user_id'] == UserData::getUserId()) : ?>
        <a class="right white fon-rgba" href="<?= url('content.edit', ['type' => 'blog', 'id' => $data['facet']['facet_id']]); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#edit"></use>
          </svg>
        </a>
      <?php endif; ?>
      <?= $data['facet']['facet_seo_title']; ?>
    </h1>
    <div class="text-sm mt10 mb-none"><?= $data['facet']['facet_short_description']; ?></div>
    <div class="right fon-rgba white ">
      <?= Html::signed([
        'type'            => 'facet',
        'id'              => $data['facet']['facet_id'],
        'content_user_id' => $data['facet']['facet_user_id'],
        'state'           => is_array($data['facet_signed']),
      ]); ?>
    </div>
  </div>
</div>