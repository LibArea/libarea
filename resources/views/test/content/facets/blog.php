<?php $blog = $data['facet'];
if ($blog['facet_is_deleted'] == 0) : ?>
  <div class="w-100">
    <div class="box-flex" style="background-image: linear-gradient(to right, white 0%, transparent 60%), url(<?= Html::coverUrl($blog['facet_cover_art'], 'blog'); ?>); background-position: 50% 50%;">
      <?= Html::image($blog['facet_img'], $blog['facet_title'], 'img-xl mr15', 'logo', 'max'); ?>
      <div class="mb-ml0 flex-auto">
        <h1 class="text-2xl">
          <?php if (UserData::checkAdmin() || $blog['facet_user_id'] == UserData::getUserId()) : ?>
            <a class="right white fon-rgba" href="<?= url('content.edit', ['type' => 'blog', 'id' => $blog['facet_id']]); ?>">
              <i class="bi-pencil bold"></i>
            </a>
          <?php endif; ?>
          <?= $blog['facet_seo_title']; ?>
        </h1>
        <div class="text-sm mt10 mb-none"><?= $blog['facet_short_description']; ?></div>
        <div class="right">
          <?= Html::signed([
            'type'            => 'facet',
            'id'              => $blog['facet_id'],
            'content_user_id' => $blog['facet_user_id'],
            'state'           => is_array($data['facet_signed']),
          ]); ?>
        </div>
      </div>
    </div>

    <main>
      <?= insert('/content/post/post', ['data' => $data]); ?>
      <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('blog', ['slug' => $blog['facet_slug']])); ?>
    </main>
  </div>
<?php else : ?>
  <div class="center">
    <i class="bi-x-octagon text-8xl"></i>
    <div class="mt5 gray"><?= __('app.remote'); ?></div>
  </div>
<?php endif; ?>