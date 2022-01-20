<?= Tpl::import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'menus' => [],
  ]
); ?>

<div class="grid grid-cols-12 gap-4 mb-gap-05 pr10 pl10 justify-between">
  <?php
  $sections = [
    [
      'bg'    => 'bg-yellow-500',
      'icon'  => 'bi-journal-text',
      'url'   => 'admin.posts',
      'title' => 'posts',
      'count' => $data['count']['count_posts'],
    ], [
      'bg'    => 'bg-indigo-300',
      'icon'  => 'bi-chat-left-text',
      'url'   => 'admin.answers',
      'title' => 'answers',
      'count' => $data['count']['count_answers'],
    ], [
      'bg'    => 'bg-blue-400',
      'icon'  => 'bi-chat-dots',
      'url'   => 'admin.comments',
      'title' => 'comments',
      'count' => $data['count']['count_comments'],
    ], [
      'bg'    => 'bg-green-600',
      'icon'  => 'bi-people',
      'url'   => 'admin.users',
      'title' => 'users',
      'count' => $data['users_count'],
    ], [
      'bg'    => 'bg-gray-500',
      'icon'  => 'bi-journals',
      'url'   => 'admin.blogs',
      'title' => 'blogs',
      'count' => $data['count']['count_blogs'],
    ], [
      'bg'    => 'bg-gray-700',
      'icon'  => 'bi-journal-richtext',
      'url'   => 'admin.pages',
      'title' => 'pages',
      'count' => $data['count']['count_pages'],
    ],
  ];

  foreach ($sections as $section) { ?>
    <div class="col-span-2 mb-col-12  <?= $section['bg']; ?> p10">
      <a class="white gray-hover text-xl" href="<?= getUrlByName($section['url']); ?>">
        <i class="bi <?= $section['icon']; ?> white right"></i>
        <?= $section['count']; ?>
        <div><?= Translate::get($section['title']); ?></div>
      </a>
    </div>
  <?php } ?>
</div>

<div class="white-box mt10 pt5 pr15 pb5 pl15">
  <h4 class="mt5 mb5"><?= Translate::get('users'); ?></h4>
  <?php foreach ($data['last_visit'] as $user) { ?>
    <div class="gray">
      id<?= $user['id']; ?>
      <a href="/@<?= $user['login']; ?>"><?= $user['login']; ?></a>
      <span class="text-sm"> — <?= lang_date($user['latest_date']); ?> (<?= $user['os']; ?>)</span>
    </div>
  <?php } ?>
</div>

<?php if ($data['posts_no_topic']) { ?>
  <div class="white-box mt10 pt5 pr15 pb5 pl15">
    <h4 class="mt5 mb5"><?= Translate::get('posts'); ?> (no-topic)</h4>
    <?php foreach ($data['posts_no_topic'] as $post) { ?>
      <div class="gray">
        <a href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['post_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<div class="mt10  pl15">
  <?= Translate::get('see more'); ?>:
  <a class="ml10 p5 pr15 pl15 mr10 white white-hover bg-red-500" href="<?= getUrlByName('admin.css'); ?>">
    <i class="bi bi-brush mr5"></i>
    CSS
  </a>
  -
  <a class="ml10 p5 pr15 pl15 mr10 white white-hover bg-orange-500" href="<?= getUrlByName('admin.tools'); ?>">
    <i class="bi bi-tools mr5"></i>
    <?= Translate::get('tools'); ?></a>

  <?= Translate::get('and'); ?>

  <a class="ml10 p5 pr15 pl15 white white-hover bg-green-700" href="<?= getUrlByName('admin.words'); ?>">
    <i class="bi bi-badge-ad mr5"></i>
    <?= Translate::get('words'); ?></a>
</div>

<div class="white-box mt10 pt5 pr15 pb5 pl15">
  <h4 class="mt5 mb5"><?= Translate::get('useful resources'); ?></h4>
  <i class="bi bi-link-45deg mr5 gray-600"></i> <a rel="noreferrer" href="https://agouti.ru">Agouti.ru</a></br>
  <i class="bi bi-github mr5 gray-600"></i> <a rel="noreferrer" href="https://discord.gg/dw47aNx5nU">Discord</a></br>
  </ul>
  <hr>
  <div class="mb20">
    <label for="name">PC:</label> <?= php_uname('s'); ?> <?php echo php_uname('r'); ?>
  </div>
  <div class="mb20">
    <label for="name">PHP:</label> <?= PHP_VERSION; ?>
  </div>
  <div class="mb20">
    <label for="name"><?= Translate::get('freely'); ?>:</label> <?= $data['bytes']; ?>
  </div>
</div>
</main>