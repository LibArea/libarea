<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>
<h4 class="uppercase-box">
  <?= __('admin.users'); ?>
  <a href="<?= url('admin.users'); ?>"><svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#more-horizontal"></use>
    </svg>
  </a>
</h4>
<?php foreach ($data['last_visit'] as $user) : ?>
  <div class="gray">
    <span class="gray-600 text-sm">id<?= $user['id']; ?></span>
    <a href="<?= url('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
    <span class="gray-600 lowercase"> — <?= langDate($user['latest_date']); ?> (<?= $user['os']; ?>)</span>
  </div>
<?php endforeach; ?>

<h4 class="uppercase-box mt15">
  <?= __('admin.search'); ?>
  <a href="<?= url('admin.logs.search'); ?>"><svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#more-horizontal"></use>
    </svg>
  </a>
</h4>
<?php foreach ($data['logs'] as $log) : ?>
  <div class="gray">
    <span class="gray-600 text-sm"><?= $log['count_results']; ?></span>
    <a target="_blank" rel="noreferrer" href="/search/go?q=<?= htmlEncode($log['request']); ?>&type=<?= $log['action_type']; ?>">
      <?= htmlEncode($log['request']); ?>
    </a>
    <span class="gray-600 lowercase">
      — (<?= __('admin.' . $log['action_type']); ?>) <?= langDate($log['add_date']); ?>
    </span>
  </div>
<?php endforeach; ?>
</main>

<aside>
  <div class="box bg-white">
    <h3 class="uppercase-box"><?= __('admin.useful_resources'); ?></h3>
    <p><svg class="icon">
        <use xlink:href="/assets/svg/icons.svg#link"></use>
      </svg> <a href="https://libarea.ru">LibArea.ru</a></p>
    <p><svg class="icon">
        <use xlink:href="/assets/svg/icons.svg#github"></use>
      </svg> <a href="https://discord.gg/adJnPEGZZZ">Discord</a></p>
    <p><svg class="icon">
        <use xlink:href="/assets/svg/icons.svg#vk"></use>
      </svg> <a href="https://vk.com/libarea">ВКонтакте</a></p>
    <hr>
    <p>PC: <?= php_uname('s'); ?> <?php echo php_uname('r'); ?></p>
    <p>PHP: <?= PHP_VERSION; ?></p>
    <p><?= __('admin.freely'); ?>: <?= $data['bytes']; ?></p>

    <?php if ($data['posts_no_topic']) : ?>
      <h3 class="uppercase-box"><?= __('admin.posts'); ?> (no facet)</h3>
      <?php foreach ($data['posts_no_topic'] as $post) : ?>
        <div class="gray">
          <a href="<?= url($post['post_type'] . '.form.edit', ['id' => $post['post_id']]) ?>">
            id:<?= $post['post_id']; ?></a> | <?= $post['post_title']; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</aside>

<?= insertTemplate('footer'); ?>