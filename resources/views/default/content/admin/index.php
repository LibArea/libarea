<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/admin', ['uid' => $uid, 'type' => $data['type']]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('admin')
  ); ?>

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('admin'); ?></p>
  </div>

  <div class="grid grid-cols-12 gap-4 pr10 pl10 justify-between">
    <div class="col-span-2 bg-yellow-500 p10">
      <a class="white gray-hover size-21" title="<?= Translate::get('posts'); ?>" rel="noreferrer gray" href="<?= getUrlByName('admin.posts'); ?>">
        <i class="bi bi-journal-text white right"></i>
        <?= $data['posts_count']; ?>
        <div class="size-15"><?= Translate::get('posts'); ?></div>
      </a>
    </div>
    <div class="col-span-2 bg-indigo-300 p10">
      <a class="white gray-hover size-21" title="<?= Translate::get('answers'); ?>" rel="noreferrer gray" href="<?= getUrlByName('admin.answers'); ?>">
        <i class="bi bi-chat-left-text white right"></i>
        <?= $data['answers_count']; ?>
        <div class="size-15"><?= Translate::get('answers'); ?></div>
      </a>
    </div>
    <div class="col-span-2 bg-green-500 p10">
      <a class="white gray-hover size-21" title="<?= Translate::get('users'); ?>" rel="noreferrer" href="<?= getUrlByName('admin.users'); ?>">
        <i class="bi bi-people white right"></i>
        <?= $data['users_count']; ?>
        <div class="size-15"><?= Translate::get('users'); ?></div>
      </a>
    </div>
    <div class="col-span-3 bg-blue-400 p10">
      <a class="white gray-hover size-21" title="<?= Translate::get('comments'); ?>" rel="noreferrer" href="<?= getUrlByName('admin.comments'); ?>">
        <i class="bi bi-chat-dots white right"></i>
        <?= $data['comments_count']; ?>
        <div class="size-15"><?= Translate::get('comments'); ?></div>
      </a>
    </div>
    <div class="col-span-3 bg-gray-700 p10">
      <a class="white gray-hover size-21" title="<?= Translate::get('topics'); ?>" rel="noreferrer" href="<?= getUrlByName('admin.topics'); ?>">
        <i class="bi bi-columns-gap white right"></i>
        <?= $data['topics_count']; ?>
        <div class="size-15"><?= Translate::get('topics'); ?></div>
      </a>
    </div>
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

  <div class="white-box mt10 pt5 pr15 pb5 pl15">
    <h4 class="mt5 mb5"><?= Translate::get('useful resources'); ?></h4>
    <i class="bi bi-link-45deg mr5 gray-light"></i> <a rel="noreferrer" href="https://agouti.ru">Agouti.ru</a></br>
    <i class="bi bi-github mr5 gray-light"></i> <a rel="noreferrer" href="https://discord.gg/dw47aNx5nU">Discord</a></br>
    </ul>
    <hr>
    <div class="mb20">
      <label for="name">PC:</label>
      <?= php_uname('s'); ?> <?php echo php_uname('r'); ?>
    </div>
    <div class="mb20">
      <label for="name">PHP:</label>
      <?= PHP_VERSION; ?>
    </div>
    <div class="mb20">
      <label for="name"><?= Translate::get('freely'); ?>:</label>
      <?= $data['bytes']; ?>
    </div>
  </div>
</main>