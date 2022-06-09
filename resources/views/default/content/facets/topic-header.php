<div class="flex box br-gray">
  <?= Html::image($topic['facet_img'], $topic['facet_title'], 'w94 mr15', 'logo', 'max'); ?>
  <div class="flex-auto">
    <h1 class="text-2xl">
      <?= $topic['facet_seo_title']; ?>
      <?php if (UserData::checkAdmin() || $topic['facet_user_id'] == UserData::getUserId()) : ?>
        <a class="right gray-600" href="<?= url('content.edit', ['type' => 'topic', 'id' => $topic['facet_id']]); ?>">
          <i class="bi-pencil"></i>
        </a>
      <?php endif; ?>
    </h1>
    <div class="text-sm gray-600 mb-none mt10"><?= $topic['facet_short_description']; ?></div>

    <div class="mt10 right">
      <?= Html::signed([
        'type'            => 'facet',
        'id'              => $topic['facet_id'],
        'content_user_id' => $topic['facet_user_id'],
        'state'           => is_array($data['facet_signed']),
      ]); ?>
    </div>
  </div>
</div>

<div class="flex justify-between mb20">
  <ul class="nav">

    <?= insert(
      '/_block/navigation/nav',
      [
        'list' =>  [
          [
            'id'      => 'facet.feed',
            'url'     => url('topic', ['slug' => $topic['facet_slug']]),
            'title'   => __('app.feed'),
          ], [
            'id'      => 'facet.recommend',
            'url'     => url('topic', ['slug' => $topic['facet_slug']]) . '/recommend',
            'title'   => __('app.recommended'),
          ], [
            'id'      => 'writers',
            'url'     => url('topic.writers', ['slug' => $topic['facet_slug']]),
            'title'   => __('app.writers'),
          ]
        ]
      ]
    ); ?>

  </ul>
  <a class="m0 gray-600" href="<?= url('topic.info', ['slug' => $topic['facet_slug']]); ?>">
    <i class="bi-info-lg"></i>
  </a>
</div>