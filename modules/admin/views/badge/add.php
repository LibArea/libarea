<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<form class="max-w-md" action="<?= url('admin.badge.create', method: 'post'); ?>" method="post">
  <?= $container->csrf()->field(); ?>
  <fieldset>
    <label for="badge_title"><?= __('admin.title'); ?></label>
    <input type="text" minlength="4" name="badge_title" value="" required>
    <div class="help">4 - 25 <?= __('admin.characters'); ?></div>
  </fieldset>
  <fieldset>
    <label for="badge_icon"><?= __('admin.icon'); ?></label>
    <textarea class="add" name="badge_icon" required></textarea>
    <div class="help"><?= __('admin.example'); ?>: &lt;svg class="icon"&gt;&lt;use xlink:href="/assets/svg/icons.svg#anchor"&gt;&lt;/use&gt;&lt;/svg&gt;</div>
  </fieldset>
  <fieldset>
    <label for="badge_tl">Tl</label>
    <input type="text" name="badge_tl" value="0" required>
    <div class="help"><?= __('admin.for'); ?> TL (0 - <?= __('admin.default'); ?>)</div>
  </fieldset>
  <fieldset>
    <label for="badge_score">Score</label>
    <input type="text" name="badge_score" value="10" required>
    <div class="help"><?= __('admin.reward_weight'); ?></div>
  </fieldset>
  <fieldset>
    <label for="badge_description"><?= __('admin.description'); ?></label>
    <textarea class="add" minlength="12" name="badge_description" required></textarea>
    <div class="help">12 - 250 <?= __('admin.characters'); ?></div>
  </fieldset>
  <?= Html::sumbit(__('admin.add')); ?>
</form>

</main>
<?= insertTemplate('footer'); ?>