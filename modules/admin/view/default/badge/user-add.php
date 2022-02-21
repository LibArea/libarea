<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="box-white">
  <form action="<?= getUrlByName('admin.user.badge.create'); ?>" method="post">
    <?= csrf_field() ?>
    <fieldset>
      <label for="post_content">
        <?= Translate::get('badge'); ?>:
        <span class="red-500"><?= $data['user']['login']; ?></span>
      </label>
      <select name="badge_id">
        <?php foreach ($data['badges'] as $badge) { ?>
          <option value="<?= $badge['badge_id']; ?>"> <?= $badge['badge_title']; ?></option>
        <?php } ?>
      </select>
      <input type="hidden" name="user_id" id="post_id" value="<?= $data['user']['id']; ?>">
    </fieldset>
    <?= sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>