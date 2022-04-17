<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="box w-100">

  <h3 class="uppercase-box"><?= __('users'); ?></h3>
  <?php foreach ($data['last_visit'] as $user) : ?>
    <div class="gray">
      <span class="gray-600 text-sm">id<?= $user['id']; ?></span>
      <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
      <span class="gray-600 lowercase"> — <?= Html::langDate($user['latest_date']); ?> (<?= $user['os']; ?>)</span>
    </div>
  <?php endforeach; ?>

  <h3 class="uppercase-box mt15"><?= __('search'); ?></h3>
  <?php foreach ($data['logs'] as $log) : ?>
    <div class="gray">
      <span class="gray-600 text-sm"><?= $log['count_results']; ?></span>
      <a target="_blank" rel="noreferrer" href="/search/go?q=<?= $log['request']; ?>&type=<?= $log['action_type']; ?>">
        <?= $log['request']; ?>
      </a>
      <span class="gray-600 lowercase">
        — (<?= __($log['action_type']); ?>) <?= Html::langDate($log['add_date']); ?>
      </span>
    </div>
  <?php endforeach; ?>

  <h3 class="uppercase-box mt15"><?= __('reply.web'); ?></h3>
  <?php foreach ($data['replys'] as $reply) : ?>
    <div class="gray">
      <a class="gray-600" href="<?= getUrlByName('profile', ['login' => $reply['login']]); ?>">
        <?= Html::image($reply['avatar'], $reply['login'], 'ava-sm', 'avatar', 'small'); ?>
        <span class="mr5">
          <?= $reply['login']; ?>
        </span>
      </a>
      <span class="mr15 ml5 gray-600 lowercase">
        <?= Html::langDate($reply['date']); ?>
      </span>
      <a class="black" href="<?= getUrlByName('web.website', ['slug' => $reply['item_domain']]); ?>">
        <i class="bi-eye"></i>
      </a>
      <div class="gray-600 text-sm mb15"><?= Content::text($reply['content'], 'line'); ?></div>
    </div>
  <?php endforeach; ?>
</div>

</main>

<aside>
  <div class="box">
    <h3 class="uppercase-box"><?= __('useful.resources'); ?></h3>
    <p><i class="bi-link-45deg mr5"></i> <a href="https://libarea.ru">LibArea.ru</a></p>
    <p><i class="bi-github mr5"></i> <a href="https://discord.gg/dw47aNx5nU">Discord</a></p>
    <hr>
    <p>PC: <?= php_uname('s'); ?> <?php echo php_uname('r'); ?></p>
    <p>PHP: <?= PHP_VERSION; ?></p>
    <p><?= __('freely'); ?>: <?= $data['bytes']; ?></p>

    <?php if ($data['posts_no_topic']) : ?>
      <h3 class="uppercase-box"><?= __('posts'); ?> (no facet)</h3>
      <?php foreach ($data['posts_no_topic'] as $post) : ?>
        <div class="gray">
          id:<?= $post['post_id']; ?> | <?= $post['post_title']; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</aside>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>