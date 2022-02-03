<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => 'add',
        'url' => getUrlByName($data['type'] . '.add'),
        'name' => Translate::get('add'),
        'icon' => 'bi bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="box-white">
  <form action="<?= getUrlByName('admin.badge.create'); ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb20 max-w780">
      <label class="block" or="post_title"><?= Translate::get('title'); ?></label>
      <input type="text" minlength="4" class="w-100 h30" name="badge_title" value="" required>
      <div class="text-sm gray-400">4 - 25 <?= Translate::get('characters'); ?></div>
    </div>
    <div class="mb20 max-w780">
      <label class="block" for="post_title"><?= Translate::get('icon'); ?></label>
      <textarea class="add" name="badge_icon" required></textarea>
      <div class="text-sm gray-400"><?= Translate::get('for example'); ?>: &lt;i title="<?= Translate::get('title'); ?>" class="bi bi-trophy"&gt;&lt;/i&gt;</div>
    </div>
    <div class="mb20">
      <label class="block" for="post_title">Tl</label>
      <input type="text" class="w-100 h30" name="badge_tl" value="0" required>
      <div class="text-sm gray-400"><?= Translate::get('for'); ?> TL (0 <?= Translate::get('by default'); ?>)</div>
    </div>
    <div class="mb20">
      <label class="block" for="post_title">Score</label>
      <input class="w-100 h30" type="text" name="badge_score" value="10" required>
      <div class="text-sm gray-400"><?= Translate::get('reward.weight'); ?></div>
    </div>
    <div class="mb20 max-w780">
      <label class="block" for="post_title"><?= Translate::get('description'); ?></label>
      <textarea class="add" minlength="12" name="badge_description" required></textarea>
      <div class="text-sm gray-400">12 - 250 <?= Translate::get('characters'); ?></div>
    </div>
    <?= sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>