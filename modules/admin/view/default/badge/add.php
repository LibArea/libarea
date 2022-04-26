<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id' => 'add',
        'url' => url($data['type'] . '.add'),
        'name' => __('add'),
        'icon' => 'bi-plus-lg'
      ]
    ]
  ]
); ?>

<div class="box bg-white max-w780">
  <form action="<?= url('admin.badge.create'); ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label for="badge_title"><?= __('title'); ?></label>
      <input type="text" minlength="4" name="badge_title" value="" required>
      <div class="help">4 - 25 <?= __('characters'); ?></div>
    </fieldset>
    <fieldset>
      <label for="badge_icon"><?= __('icon'); ?></label>
      <textarea class="add" name="badge_icon" required></textarea>
      <div class="help"><?= __('for.example'); ?>: &lt;i title="<?= __('title'); ?>" class="bi-trophy"&gt;&lt;/i&gt;</div>
    </fieldset>
    <fieldset>
      <label for="badge_tl">Tl</label>
      <input type="text" name="badge_tl" value="0" required>
      <div class="help"><?= __('for'); ?> TL (0 <?= __('by.default'); ?>)</div>
    </fieldset>
    <fieldset>
      <label for="badge_score">Score</label>
      <input type="text" name="badge_score" value="10" required>
      <div class="help"><?= __('reward.weight'); ?></div>
    </fieldset>
    <fieldset>
      <label for="badge_description"><?= __('description'); ?></label>
      <textarea class="add" minlength="12" name="badge_description" required></textarea>
      <div class="help">12 - 250 <?= __('characters'); ?></div>
    </fieldset>
    <?= Html::sumbit(__('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>