<div class="flex box box-fon gap">
  <?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-xl', 'logo', 'max'); ?>
  <div class="flex-auto">
    <h1 class="text-2xl mb-text-xl m0">
      <?php if (UserData::checkAdmin() || $topic['facet_user_id'] == UserData::getUserId()) : ?>
        <a class="right gray-600" href="<?= url('content.edit', ['type' => 'topic', 'id' => $topic['facet_id']]); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#edit"></use>
          </svg>
        </a>
      <?php endif; ?>
      <?= $topic['facet_seo_title']; ?>
    </h1>
    <div class="text-sm gray-600 mb-none mt10"><?= $topic['facet_short_description']; ?></div>

    <div class="mr120 right gray-600">
      <?= Html::signed([
        'type'            => 'facet',
        'id'              => $topic['facet_id'],
        'content_user_id' => $topic['facet_user_id'],
        'state'           => is_array($data['facet_signed']),
      ]); ?>
      <a class="gray-600 ml30" href="<?= url('topic.info', ['slug' => $topic['facet_slug']]); ?>">
        <svg class="icons">
          <use xlink:href="/assets/svg/icons.svg#info"></use>
        </svg>
      </a>
    </div>
  </div>
</div>

<div class="flex justify-between mb20">
  <ul class="nav scroll">

    <?= insert(
      '/_block/navigation/nav',
      [
        'list' =>  [
          [
            'id'      => 'facet.feed',
            'url'     => url('topic', ['slug' => $topic['facet_slug']]),
            'title'   => __('app.feed'),
          ], [
            'id'    => 'facet.posts',
            'url'   => url('topic', ['slug' => $topic['facet_slug']]) . '/posts',
            'title' => __('app.posts'),
          ], [
            'id'    => 'facet.questions',
            'url'   => url('topic', ['slug' => $topic['facet_slug']]) . '/questions',
            'title' => __('app.questions'),
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
</div>