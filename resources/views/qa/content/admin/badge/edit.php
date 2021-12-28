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
    'pages'   => []
  ]
); ?>

<div class="bg-white br-box-gray p15 max-w780">
  <form action="/admin/badge/edit/<?= $data['badge']['badge_id']; ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb20">
      <label for="post_title">Id</label>
      <?= $data['badge']['badge_id']; ?>
    </div>
    <div class="mb20">
      <label class="block" for="post_title">Title</label>
      <input class="w-100 h30" minlength="4" type="text" name="badge_title" value="<?= $data['badge']['badge_title']; ?>" required>
      <div class="text-sm gray-400">4 - 25 <?= Translate::get('characters'); ?></div>
    </div>
    <div class="mb20">
      <label class="block" for="post_title">Icon</label>
      <textarea class="add" name="badge_icon" required><?= $data['badge']['badge_icon']; ?></textarea>
      <div class="text-sm gray-400"><?= Translate::get('for example'); ?>: &lt;i title="<?= Translate::get('title'); ?>" class="bi bi-trophy"&gt;&lt;/i&gt; </div>
    </div>
    <div class="mb20">
      <label class="block" for="post_title">Tl</label>
      <input class="w-100 h30" type="text" name="badge_tl" value="<?= $data['badge']['badge_tl']; ?>" required>
      <div class="text-sm gray-400"><?= Translate::get('for'); ?> TL (0 <?= Translate::get('by default'); ?>)</div>
    </div>
    <div class="mb20">
      <label class="block" for="post_title">Score</label>
      <input class="w-100 h30" type="text" name="badge_score" value="<?= $data['badge']['badge_score']; ?>" required>
      <div class="text-sm gray-400"><?= Translate::get('reward weight'); ?></div>
    </div>
    <div class="mb20">
      <label class="block" for="post_title"><?= Translate::get('description'); ?></label>
      <textarea class="add" minlength="12" name="badge_description" required><?= $data['badge']['badge_description']; ?></textarea>
      <div class="text-sm gray-400">12 - 250 <?= Translate::get('characters'); ?></div>
    </div>
    <?= sumbit(Translate::get('edit')); ?>
  </form>
</div>
</main>