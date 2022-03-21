<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="box-white w-100">
  <div class="w-50 left mb15 mb-w-100">
    <h3 class="uppercase-box"><?= Translate::get('users'); ?></h3>
    <?php foreach ($data['last_visit'] as $user) { ?>
      <div class="gray">
        <span class="gray-600 text-sm">id<?= $user['id']; ?></span>
        <a href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>"><?= $user['login']; ?></a>
        <span class="gray-600 lowercase"> — <?= lang_date($user['latest_date']); ?> (<?= $user['os']; ?>)</span>
      </div>
    <?php } ?>
  </div>

  <div class="w-50 left mb-w-100">
    <h3 class="uppercase-box"><?= Translate::get('search'); ?></h3>
    <?php foreach ($data['logs'] as $log) { ?>
      <div class="gray">
        <span class="gray-600 text-sm"><?= $log['count_results']; ?></span>
        <a target="_blank" rel="noreferrer" href="/search?q=<?= $log['request']; ?>&type=<?= $log['action_type']; ?>">    
          <?= $log['request']; ?>
        </a>
        <span class="gray-600 lowercase">
          — (<?= Translate::get($log['action_type']); ?>) <?= lang_date($log['add_date']); ?>
        </span>
      </div>
    <?php } ?>
  </div>

  <div class="w-100 p0">&nbsp;</div>

  <?php if ($data['posts_no_topic']) { ?>
    <h3 class="uppercase-box"><?= Translate::get('posts'); ?> (no facet)</h3>
    <?php foreach ($data['posts_no_topic'] as $post) { ?>
      <div class="gray">
        id:<?= $post['post_id']; ?> | <?= $post['post_title']; ?>
      </div>
    <?php } ?>
  <?php } ?>

  <h3 class="uppercase-box mt15"><?= Translate::get('useful.resources'); ?></h3>
  <p><i class="bi-link-45deg mr5"></i> <a href="https://agouti.ru">Agouti.ru</a></p>
  <p><i class="bi-github mr5"></i> <a href="https://discord.gg/dw47aNx5nU">Discord</a></p>
  <hr>
  <p>PC: <?= php_uname('s'); ?> <?php echo php_uname('r'); ?></p>
  <p>PHP: <?= PHP_VERSION; ?></p>
  <p><?= Translate::get('freely'); ?>: <?= $data['bytes']; ?></p>
</div>
</main>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>