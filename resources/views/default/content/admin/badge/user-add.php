<div class="sticky mt5 top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.admin'),
      ); ?>
</div>

<?= import(
  '/content/admin/menu',
  [
    'type'    => $data['type'],
    'sheet'   => $data['sheet'],
    'pages'   => []
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <form action="<?= getUrlByName('admin.user.badge.create'); ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb20">
      <label class="block mb5" for="post_content">
        <?= Translate::get('badge'); ?>:
        <span class="red-500"><?= $data['user']['user_login']; ?></span>
      </label>
      <select class="w-100 h30" name="badge_id">
        <?php foreach ($data['badges'] as $badge) { ?>
          <option value="<?= $badge['badge_id']; ?>"> <?= $badge['badge_title']; ?></option>
        <?php } ?>
      </select>
      <input type="hidden" name="user_id" id="post_id" value="<?= $data['user']['user_id']; ?>">
    </div>
    <?= sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>