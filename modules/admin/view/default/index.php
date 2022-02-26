<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="w-100">
  <div class="w-50 left mb-w-100">
    <div class="box-white">
      <h3 class="uppercase-box"><?= Translate::get('content'); ?></h3>
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
  <div class="w-50 left mb-w-100">
    <div class="box-white ml20">
      <h3 class="uppercase-box"><?= Translate::get('users'); ?></h3>
      <?php foreach ($data['last_visit'] as $user) { ?>
        <div class="gray">
          <span class="gray-400 text-sm">id<?= $user['id']; ?></span>
          <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
          <span class="gray-400"> — <?= lang_date($user['latest_date']); ?> (<?= $user['os']; ?>)</span>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<?php if ($data['posts_no_topic']) { ?>
  <div class="white-box mt10 pt5 pr15 pb5 pl15">
    <h3 class="uppercase-box"><?= Translate::get('posts'); ?> (no-topic)</h3>
    <?php foreach ($data['posts_no_topic'] as $post) { ?>
      <div class="gray">
        <a href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['post_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<div class="mt10 p15 gray-600">
  <h3 class="uppercase-box"><?= Translate::get('useful.resources'); ?></h3>
  <p><i class="bi bi-link-45deg mr5"></i> <a href="https://agouti.ru">Agouti.ru</a></p>
  <p><i class="bi bi-github mr5"></i> <a href="https://discord.gg/dw47aNx5nU">Discord</a></p>
  <hr>
  <p>PC: <?= php_uname('s'); ?> <?php echo php_uname('r'); ?></p>
  <p>PHP: <?= PHP_VERSION; ?></p>
  <p><?= Translate::get('freely'); ?>: <?= $data['bytes']; ?></p>
</div>
</main>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>