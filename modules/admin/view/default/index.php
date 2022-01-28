<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="w-100">
  <div class="w-50 left">
    <div class="bg-white br-box-gray p15 mr20">
      <h4 class="uppercase-box"><?= Translate::get('content'); ?></h4>
      <?php
      $sections = [
        [
          'icon'  => 'bi-journal-text',
          'url'   => 'feed.all',
          'title' => 'posts',
          'count' => $data['count']['count_posts'],
        ], [
          'icon'  => 'bi-chat-left-text',
          'url'   => 'answers',
          'title' => 'answers',
          'count' => $data['count']['count_answers'],
        ], [
          'icon'  => 'bi-chat-dots',
          'url'   => 'comments',
          'title' => 'comments',
          'count' => $data['count']['count_comments'],
        ], [
          'icon'  => 'bi-people',
          'url'   => 'admin.users',
          'title' => 'users',
          'count' => $data['users_count'],
        ], [
          'icon'  => 'bi-journals',
          'url'   => 'admin.blogs',
          'title' => 'blogs',
          'count' => $data['count']['count_blogs'],
        ], [
          'icon'  => 'bi-journal-richtext',
          'url'   => 'admin.pages',
          'title' => 'pages',
          'count' => $data['count']['count_pages'],
        ],
      ];

      foreach ($sections as $section) { ?>
        <a class="text-lg block mb5" href="<?= getUrlByName($section['url']); ?>">
          <i class="bi <?= $section['icon']; ?> gray-400 mr5"></i>
          <?= Translate::get($section['title']); ?>
          <sup class="gray-400"><?= $section['count']; ?><sup>
        </a>
      <?php } ?>

    </div>
  </div>
  <div class="w-50 left">
    <div class="bg-white br-box-gray p15 ml20">
      <h4 class="uppercase-box"><?= Translate::get('users'); ?></h4>
      <?php foreach ($data['last_visit'] as $user) { ?>
        <div class="gray">
          <span class="gray-400 text-sm">id<?= $user['id']; ?></span>
          <a href="/@<?= $user['login']; ?>"><?= $user['login']; ?></a>
          <span class="gray-400"> — <?= lang_date($user['latest_date']); ?> (<?= $user['os']; ?>)</span>
        </div>
      <?php } ?>
    </div>
  </div>
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

<div class="pt15">
  <a class="btn-small inline mr15 ml15 white bg-red-500" href="<?= getUrlByName('admin.css'); ?>">
    <i class="bi bi-brush mr5"></i>
    CSS
  </a>

  <a class="btn-small mr15 inline white bg-orange-500" href="<?= getUrlByName('admin.tools'); ?>">
    <i class="bi bi-tools mr5"></i>
    <?= Translate::get('tools'); ?>
  </a>

  <a class="btn-small mr15 white inline bg-green-700" href="<?= getUrlByName('admin.words'); ?>">
    <i class="bi bi-badge-ad mr5"></i>
    <?= Translate::get('words'); ?>
  </a>
</div>

<div class="white-box mt10 pr15 pb5 pl15">
  <h4 class="mt5 mb5"><?= Translate::get('useful.resources'); ?></h4>
  <i class="bi bi-link-45deg mr5 gray-600"></i> <a rel="noreferrer" href="https://agouti.ru">Agouti.ru</a></br>
  <i class="bi bi-github mr5 gray-600"></i> <a rel="noreferrer" href="https://discord.gg/dw47aNx5nU">Discord</a></br>
  </ul>
  <hr>
  <div class="mb10">
    <label for="name">PC:</label> <?= php_uname('s'); ?> <?php echo php_uname('r'); ?>
  </div>
  <div class="mb10">
    <label for="name">PHP:</label> <?= PHP_VERSION; ?>
  </div>
  <div class="mb10">
    <label for="name"><?= Translate::get('freely'); ?>:</label> <?= $data['bytes']; ?>
  </div>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>