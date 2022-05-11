<div class="box-flex">
  <div class="mb-none">
    <?= Html::image($topic['facet_img'], $topic['facet_title'], 'w94 br-gray mr15', 'logo', 'max'); ?>
  </div>
  <div class="flex-auto">
    <h1 class="text-2xl">
      <?= $topic['facet_seo_title']; ?>
      <?php if (UserData::checkAdmin() || $topic['facet_user_id'] == UserData::getUserId()) : ?>
        <a class="right gray-600" href="<?= url('content.edit', ['type' => 'topic', 'id' => $topic['facet_id']]); ?>">
          <i class="bi-pencil"></i>
        </a>
      <?php endif; ?>
    </h1>
    <div class="text-sm gray-600"><?= $topic['facet_short_description']; ?></div>

    <div class="mt15 right">
      <?= Html::signed([
        'type'            => 'facet',
        'id'              => $topic['facet_id'],
        'content_user_id' => $topic['facet_user_id'],
        'state'           => is_array($data['facet_signed']),
      ]); ?>
    </div>

    <?= Tpl::insert('/_block/facet/focus-users', [
      'topic_focus_count' => $topic['facet_focus_count'],
      'focus_users'       => $data['focus_users'] ?? '',
    ]); ?>
  </div>
</div>

<div class="box-flex justify-between">
  <p class="m0 text-xl mb-none"><?= __('app.' . $data['type']); ?></p>
  <ul class="nav">

    <?= Tpl::insert(
      '/_block/navigation/nav',
      [
        'list' =>  [
          [
            'id'      => 'facet.feed',
            'url'     => url('topic', ['slug' => $topic['facet_slug']]),
            'title'   => __('app.feed'),
            'icon'    => 'bi-sort-down'
          ], [
            'id'      => 'facet.recommend',
            'url'     => url('topic', ['slug' => $topic['facet_slug']]) . '/recommend',
            'title'   => __('app.recommended'),
            'icon'    => 'bi-lightning'
          ],  [
            'id'      => 'writers',
            'url'     => url('topic.writers', ['slug' => $topic['facet_slug']]),
            'title'   => __('app.writers'),
            'icon'    => 'bi-award'
          ], [
            'id'      => 'info',
            'url'     => url('topic.info', ['slug' => $topic['facet_slug']]),
            'title'   => '',
            'icon'    => 'bi-info-lg'
          ],
        ]
      ]
    ); ?>

  </ul>
</div>