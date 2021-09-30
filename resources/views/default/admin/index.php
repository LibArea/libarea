<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('admin')); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
  </div>

  <div class="grid grid-cols-12 gap-4 pr10 pl10 justify-between">
    <div class="col-span-3 bg-yellow-500 p10">
      <a class="white" title="<?= lang('posts'); ?>" rel="noreferrer gray" href="/admin/posts">
        <i class="icon-book-open size-13 right"></i>
        <div class="count white"><?= $data['posts_count']; ?></div>
        <div class="size-15 white"><?= lang('posts'); ?></div>
      </a>
    </div>
    <div class="col-span-3 bg-indigo-300 p10">
      <a class="white" title="<?= lang('answers-n'); ?>" rel="noreferrer gray" href="/admin/answers">
        <i class="icon-comment-empty size-13 right"></i>
        <div class="count white"><?= $data['answers_count']; ?></div>
        <div class="size-15 white"><?= lang('answers-n'); ?></div>
      </a>
    </div>
    <div class="col-span-3 bg-green-500 p10">
      <a class="white" title="<?= lang('users'); ?>" rel="noreferrer" href="/admin/users">
        <i class="icon-user-o icon size-13 right"></i>
        <div class="count white"><?= $data['users_count']; ?></div>
        <div class="size-15 white"><?= lang('users'); ?></div>
      </a>
    </div>
    <div class="col-span-3 bg-blue-400 p10">
      <a class="white" title="<?= lang('comments-n'); ?>" rel="noreferrer" href="/admin/comments">
        <i class="icon-commenting-o size-13 right"></i>
        <div class="count white"><?= $data['comments_count']; ?></div>
        <div class="size-15 white"><?= lang('comments-n'); ?></div>
      </a>
    </div>
  </div>
  <div class="white-box mt10 pt5 pr15 pb5 pl15">
    <h4 class="mt5 mb5"><?= lang('users'); ?></h4>
    <?php foreach ($data['last_visit'] as $user) { ?>
      <div class="gray size-15">
        id<?= $user['user_id']; ?>
        <a href="/u/<?= $user['user_login']; ?>"><?= $user['user_login']; ?></a>
        <span class="size-13"> — <?= lang_date($user['latest_date']); ?> (<?= $user['os']; ?>)</span>
      </div>
    <?php } ?>
  </div>

  <div class="white-box mt10 pt5 pr15 pb5 pl15">
    <h4 class="mt5 mb5"><?= lang('useful resources'); ?></h4>
    <i class="icon-record-outline gray-light"></i> <a rel="noreferrer" href="https://agouti.ru">Agouti.ru</a></br>
    <i class="icon-record-outline gray-light"></i> <a rel="noreferrer" href="https://discord.gg/dw47aNx5nU">Discord</a></br>
    </ul>
    <hr>
    <div class="boxline">
      <label for="name">PC:</label>
      <?= php_uname('s'); ?> <?php echo php_uname('r'); ?>
    </div>
    <div class="boxline">
      <label for="name">PHP:</label>
      <?= PHP_VERSION; ?>
    </div>
    <div class="boxline">
      <label for="name"><?= lang('freely'); ?>:</label>
      <?= $data['bytes']; ?>
    </div>
  </div>
</main>