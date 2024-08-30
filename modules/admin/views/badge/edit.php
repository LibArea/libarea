<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<form action="<?= url('admin.badge.edit', ['id' => $data['badge']['badge_id']], method: 'post'); ?>" method="post">
  <?= $container->csrf()->field(); ?>
  <fieldset>
    Id. <?= $data['badge']['badge_id']; ?>
  </fieldset>
  <fieldset>
    <label for="badge_title"><?= __('admin.title'); ?></label>
    <input minlength="4" type="text" name="badge_title" value="<?= $data['badge']['badge_title']; ?>" required>
    <div class="help">4 - 25 <?= __('admin.characters'); ?></div>
  </fieldset>
  <fieldset>
    <label for="badge_icon"><?= __('admin.icon'); ?></label>
    <textarea class="add" name="badge_icon" required><?= $data['badge']['badge_icon']; ?></textarea>
    <div class="help"><?= __('admin.example'); ?>: &lt;svg class="icon"&gt;&lt;use xlink:href="/assets/svg/icons.svg#anchor"&gt;&lt;/use&gt;&lt;/svg&gt; - <a href="<?= url('admin.css'); ?>">css</a></div>
  </fieldset>
  <fieldset>
    <label for="badge_tl">Tl</label>
    <input type="text" name="badge_tl" value="<?= $data['badge']['badge_tl']; ?>" required>
    <div class="help"><?= __('admin.for'); ?> TL (0 <?= __('admin.default'); ?>)</div>
  </fieldset>
  <fieldset>
    <label for="badge_score">Score</label>
    <input type="text" name="badge_score" value="<?= $data['badge']['badge_score']; ?>" required>
    <div class="help"><?= __('admin.reward_weight'); ?></div>
  </fieldset>
  <fieldset>
    <label for="badge_description"><?= __('admin.description'); ?></label>
    <textarea class="add" minlength="12" name="badge_description" required><?= $data['badge']['badge_description']; ?></textarea>
    <div class="help">12 - 250 <?= __('admin.characters'); ?></div>
  </fieldset>
  <?= Html::sumbit(__('admin.edit')); ?>
</form>
</main>
<?= insertTemplate('footer'); ?>