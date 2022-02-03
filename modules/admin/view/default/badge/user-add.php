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
    <div class="mb20">
      <label class="block mb5" for="post_content">
        <?= Translate::get('badge'); ?>:
        <span class="red-500"><?= $data['user']['login']; ?></span>
      </label>
      <select class="w-100 h30" name="badge_id">
        <?php foreach ($data['badges'] as $badge) { ?>
          <option value="<?= $badge['badge_id']; ?>"> <?= $badge['badge_title']; ?></option>
        <?php } ?>
      </select>
      <input type="hidden" name="user_id" id="post_id" value="<?= $data['user']['id']; ?>">
    </div>
    <?= sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>