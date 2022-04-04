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
        'icon' => 'bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="box-white max-w780">
  <form action="<?= getUrlByName('admin.badge.create'); ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label for="badge_title"><?= Translate::get('title'); ?></label>
      <input type="text" minlength="4" name="badge_title" value="" required>
      <div class="help">4 - 25 <?= Translate::get('characters'); ?></div>
    </fieldset>
    <fieldset>
      <label for="badge_icon"><?= Translate::get('icon'); ?></label>
      <textarea class="add" name="badge_icon" required></textarea>
      <div class="help"><?= Translate::get('for.example'); ?>: &lt;i title="<?= Translate::get('title'); ?>" class="bi-trophy"&gt;&lt;/i&gt;</div>
    </fieldset>
    <fieldset>
      <label for="badge_tl">Tl</label>
      <input type="text" name="badge_tl" value="0" required>
      <div class="help"><?= Translate::get('for'); ?> TL (0 <?= Translate::get('by.default'); ?>)</div>
    </fieldset>
    <fieldset>
      <label for="badge_score">Score</label>
      <input type="text" name="badge_score" value="10" required>
      <div class="help"><?= Translate::get('reward.weight'); ?></div>
    </fieldset>
    <fieldset>
      <label for="badge_description"><?= Translate::get('description'); ?></label>
      <textarea class="add" minlength="12" name="badge_description" required></textarea>
      <div class="help">12 - 250 <?= Translate::get('characters'); ?></div>
    </fieldset>
    <?= Html::sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>