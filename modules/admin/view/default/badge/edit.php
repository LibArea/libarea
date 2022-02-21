<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box-white">
  <form action="/admin/badge/edit/<?= $data['badge']['badge_id']; ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label>Id</label>
      <?= $data['badge']['badge_id']; ?>
    </fieldset>
    <fieldset>
      <label for="badge_title"><?= Translate::get('title'); ?></label>
      <input minlength="4" type="text" name="badge_title" value="<?= $data['badge']['badge_title']; ?>" required>
      <div class="help">4 - 25 <?= Translate::get('characters'); ?></div>
    </fieldset>
    <fieldset>
      <label for="badge_icon"><?= Translate::get('icon'); ?></label>
      <textarea class="add" name="badge_icon" required><?= $data['badge']['badge_icon']; ?></textarea>
      <div class="help"><?= Translate::get('for example'); ?>: &lt;i title="<?= Translate::get('title'); ?>" class="bi bi-trophy"&gt;&lt;/i&gt; </div>
    </fieldset>
    <fieldset>
      <label for="badge_tl">Tl</label>
      <input type="text" name="badge_tl" value="<?= $data['badge']['badge_tl']; ?>" required>
      <div class="help"><?= Translate::get('for'); ?> TL (0 <?= Translate::get('by default'); ?>)</div>
    </fieldset>
    <fieldset>
      <label for="badge_score">Score</label>
      <input type="text" name="badge_score" value="<?= $data['badge']['badge_score']; ?>" required>
      <div class="help"><?= Translate::get('reward.weight'); ?></div>
    </fieldset>
    <fieldset>
      <label for="badge_description"><?= Translate::get('description'); ?></label>
      <textarea class="add" minlength="12" name="badge_description" required><?= $data['badge']['badge_description']; ?></textarea>
      <div class="help">12 - 250 <?= Translate::get('characters'); ?></div>
    </fieldset>
    <?= sumbit(Translate::get('edit')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>