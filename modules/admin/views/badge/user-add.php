<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<form action="<?= url('admin.user.badge.create', method: 'post'); ?>" method="post">
  <?= $container->csrf()->field(); ?>
  <fieldset>
    <label for="post_content">
      <?= __('admin.badge'); ?>:
      <span class="red"><?= $data['user']['login']; ?></span>
    </label>
    <select name="badge_id">
      <?php foreach ($data['badges'] as $badge) { ?>
        <option value="<?= $badge['badge_id']; ?>"> <?= $badge['badge_title']; ?></option>
      <?php } ?>
    </select>
    <input type="hidden" name="user_id" value="<?= $data['user']['id']; ?>">
  </fieldset>
  <?= Html::sumbit(__('admin.add')); ?>
</form>

</main>
<?= insertTemplate('footer'); ?>