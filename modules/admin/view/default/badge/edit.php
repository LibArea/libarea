<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box-white">
  <form action="<?= getUrlByName('admin.badge.change', ['id' => $data['badge']['badge_id']]); ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label>Id</label>
      <?= $data['badge']['badge_id']; ?>
    </fieldset>
    <fieldset>
      <label for="badge_title"><?= __('title'); ?></label>
      <input minlength="4" type="text" name="badge_title" value="<?= $data['badge']['badge_title']; ?>" required>
      <div class="help">4 - 25 <?= __('characters'); ?></div>
    </fieldset>
    <fieldset>
      <label for="badge_icon"><?= __('icon'); ?></label>
      <textarea class="add" name="badge_icon" required><?= $data['badge']['badge_icon']; ?></textarea>
      <div class="help"><?= __('for.example'); ?>: &lt;i title="<?= __('title'); ?>" class="bi-trophy"&gt;&lt;/i&gt; </div>
    </fieldset>
    <fieldset>
      <label for="badge_tl">Tl</label>
      <input type="text" name="badge_tl" value="<?= $data['badge']['badge_tl']; ?>" required>
      <div class="help"><?= __('for'); ?> TL (0 <?= __('by.default'); ?>)</div>
    </fieldset>
    <fieldset>
      <label for="badge_score">Score</label>
      <input type="text" name="badge_score" value="<?= $data['badge']['badge_score']; ?>" required>
      <div class="help"><?= __('reward.weight'); ?></div>
    </fieldset>
    <fieldset>
      <label for="badge_description"><?= __('description'); ?></label>
      <textarea class="add" minlength="12" name="badge_description" required><?= $data['badge']['badge_description']; ?></textarea>
      <div class="help">12 - 250 <?= __('characters'); ?></div>
    </fieldset>
    <?= Html::sumbit(__('edit')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>