<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box bg-white">
  <form action="<?= url('admin.user.badge.create'); ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label for="post_content">
        <?= __('badge'); ?>:
        <span class="red"><?= $data['user']['login']; ?></span>
      </label>
      <select name="badge_id">
        <?php foreach ($data['badges'] as $badge) { ?>
          <option value="<?= $badge['badge_id']; ?>"> <?= $badge['badge_title']; ?></option>
        <?php } ?>
      </select>
      <input type="hidden" name="user_id" id="post_id" value="<?= $data['user']['id']; ?>">
    </fieldset>
    <?= Html::sumbit(__('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>