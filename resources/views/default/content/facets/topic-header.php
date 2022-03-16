<div class="box-flex-white">
  <div class="mb-none">
    <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w94 br-box-gray mr15'); ?>
  </div>
  <div class="flex-auto">
    <h1 class="text-2xl">
      <?= $topic['facet_seo_title']; ?>
      <?php if (UserData::checkAdmin() || $topic['facet_user_id'] == $user['id']) { ?>
        <a class="right gray-600" href="<?= getUrlByName('content.edit', ['type' => 'topic', 'id' => $topic['facet_id']]); ?>">
          <i class="bi-pencil"></i>
        </a>
      <?php } ?>
    </h1>
    <div class="text-sm gray-400"><?= $topic['facet_short_description']; ?></div>

    <div class="mt15 right">
      <?= Tpl::import('/_block/facet/signed', [
        'user'           => $user,
        'topic'          => $topic,
        'topic_signed'   => is_array($data['facet_signed']),
      ]); ?>
    </div>

    <?= Tpl::import('/_block/facet/focus-users', [
      'user'              => $user,
      'topic_focus_count' => $topic['facet_focus_count'],
      'focus_users'       => $data['focus_users'] ?? '',
    ]); ?>
  </div>
</div>

<div class="box-flex-white">
  <p class="m0 text-xl mb-none"><?= Translate::get($data['type']); ?></p>
  <ul class="nav">

    <?= tabs_nav(
      'nav',
      $data['sheet'],
      $user,
      $pages = [
        [
          'id'      => 'facet.feed',
          'url'     => getUrlByName('topic', ['slug' => $topic['facet_slug']]),
          'title'   => Translate::get('feed'),
          'icon'    => 'bi-sort-down'
        ], [
          'id'      => 'facet.recommend',
          'url'     => getUrlByName('topic', ['slug' => $topic['facet_slug']]) . '/recommend',
          'title'   => Translate::get('recommended'),
          'icon'    => 'bi-lightning'
        ],  [
          'id'      => 'writers',
          'url'     => getUrlByName('topic.writers', ['slug' => $topic['facet_slug']]),
          'title'   => Translate::get('writers'),
          'icon'    => 'bi-award'
        ], [
          'id'      => 'info',
          'url'     => getUrlByName('topic.info', ['slug' => $topic['facet_slug']]),
          'title'   => '',
          'icon'    => 'bi-info-lg'
        ],
      ]
    ); ?>

  </ul>
</div>