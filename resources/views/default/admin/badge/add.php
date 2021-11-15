<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/menu-admin', ['sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    getUrlByName('admin.badges'),
    Translate::get('badges'),
    Translate::get('add badge')
  ); ?>

  <div class="bg-white br-box-gray p15">
    <form action="<?= getUrlByName('admin.badge.create'); ?>" method="post">
      <?= csrf_field() ?>
      <div class="mb20 max-w780">
        <label class="block" or="post_title">Title</label>
        <input type="text" minlength="4" class="w-100 h30" name="badge_title" value="" required>
        <div class="size-14 gray-light-2">4 - 25 <?= Translate::get('characters'); ?></div>
      </div>
      <div class="mb20 max-w780">
        <label class="block" for="post_title">Icon</label>
        <textarea class="add" name="badge_icon" required></textarea>
        <div class="size-14 gray-light-2"><?= Translate::get('for example'); ?>: &lt;i title="<?= Translate::get('title'); ?>" class="bi bi-trophy"&gt;&lt;/i&gt;</div>
      </div>
      <div class="mb20">
        <label class="block" for="post_title">Tl</label>
        <input type="text" class="w-100 h30" name="badge_tl" value="0" required>
        <div class="size-14 gray-light-2"><?= Translate::get('for'); ?> TL (0 <?= Translate::get('by default'); ?>)</div>
      </div>
      <div class="mb20">
        <label class="block" for="post_title">Score</label>
        <input class="w-100 h30" type="text" name="badge_score" value="10" required>
        <div class="size-14 gray-light-2"><?= Translate::get('reward weight'); ?></div>
      </div>
      <div class="mb20 max-w780">
        <label class="block" for="post_title"><?= Translate::get('description'); ?></label>
        <textarea class="add" minlength="12" name="badge_description" required></textarea>
        <div class="size-14 gray-light-2">12 - 250 <?= Translate::get('characters'); ?></div>
      </div>
      <?= sumbit(Translate::get('add')); ?>
    </form>
  </div>
</main>