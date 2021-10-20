<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), '/admin/badges', lang('badges'), lang('edit badge')); ?>

  <div class="box badges">
    <form action="/admin/badge/edit/<?= $data['badge']['badge_id']; ?>" method="post">
      <?= csrf_field() ?>
      <div class="boxline max-w780">
        <label for="post_title">Id</label>
        <?= $data['badge']['badge_id']; ?>
      </div>
      <div class="boxline max-w780">
        <label class="block" for="post_title">Title</label>
        <input class="w-100 h30" minlength="4" type="text" name="badge_title" value="<?= $data['badge']['badge_title']; ?>" required>
        <div class="size-14 gray-light-2">4 - 25 <?= lang('characters'); ?></div>
      </div>
      <div class="boxline max-w780">
        <label class="block" for="post_title">Icon</label>
        <textarea class="add" name="badge_icon" required><?= $data['badge']['badge_icon']; ?></textarea>
        <div class="size-14 gray-light-2"><?= lang('for example'); ?>: &lt;i title="<?= lang('title'); ?>" class="bi bi-trophy"&gt;&lt;/i&gt; </div>
      </div>
      <div class="boxline">
        <label class="block" for="post_title">Tl</label>
        <input class="w-100 h30" type="text" name="badge_tl" value="<?= $data['badge']['badge_tl']; ?>" required>
        <div class="size-14 gray-light-2"><?= lang('for'); ?> TL (0 <?= lang('by default'); ?>)</div>
      </div>
      <div class="boxline">
        <label class="block" for="post_title">Score</label>
        <input class="w-100 h30" type="text" name="badge_score" value="<?= $data['badge']['badge_score']; ?>" required>
        <div class="size-14 gray-light-2"><?= lang('reward weight'); ?></div>
      </div>
      <div class="boxline max-w780">
        <label class="block" for="post_title"><?= lang('description'); ?></label>
        <textarea class="add" minlength="12" name="badge_description" required><?= $data['badge']['badge_description']; ?></textarea>
        <div class="size-14 gray-light-2">12 - 250 <?= lang('characters'); ?></div>
      </div>
      <input type="submit" class="button block br-rd5 white" name="submit" value="<?= lang('edit'); ?>" />
    </form>
  </div>
</main>