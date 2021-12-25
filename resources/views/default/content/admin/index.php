<div class="sticky mt5 top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.admin'),
  ); ?>
</div>

<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'pages'   => [],
  ]
); ?>

<div class="grid grid-cols-12 gap-4 pr10 pl10 justify-between">
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
    <div class="col-span-2 <?= $section['bg']; ?> p10">
      <a class="white gray-hover size-21" href="<?= getUrlByName($section['url']); ?>">
        <i class="bi <?= $section['icon']; ?> white right"></i>
        <?= $section['count']; ?>
        <div class="size-15"><?= Translate::get($section['title']); ?></div>
      </a>
    </div>
  <?php } ?>
</div>

<div class="white-box mt10 pt5 pr15 pb5 pl15">
  <h4 class="mt5 mb5"><?= Translate::get('users'); ?></h4>
  <?php foreach ($data['last_visit'] as $user) { ?>
    <div class="gray size-15">
      id<?= $user['user_id']; ?>
      <a href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>"><?= $user['user_login']; ?></a>
      <span class="size-13"> — <?= lang_date($user['latest_date']); ?> (<?= $user['os']; ?>)</span>
    </div>
  <?php } ?>
</div>

<?php if ($data['posts_no_topic']) { ?>
  <div class="white-box mt10 pt5 pr15 pb5 pl15">
    <h4 class="mt5 mb5"><?= Translate::get('posts'); ?> (no-topic)</h4>
    <?php foreach ($data['posts_no_topic'] as $post) { ?>
      <div class="gray size-15">
        <a href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['post_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<div class="mt10  pl15">
  <?= Translate::get('see more'); ?>: <a title="css" class="p5 pr15 pl15 white white-hover bg-red-500" href="<?= getUrlByName('admin.сss'); ?>">CSS</a>
</div>

<div class="white-box mt10 pt5 pr15 pb5 pl15">
  <h4 class="mt5 mb5"><?= Translate::get('useful resources'); ?></h4>
  <i class="bi bi-link-45deg mr5 gray-light"></i> <a rel="noreferrer" href="https://agouti.ru">Agouti.ru</a></br>
  <i class="bi bi-github mr5 gray-light"></i> <a rel="noreferrer" href="https://discord.gg/dw47aNx5nU">Discord</a></br>
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