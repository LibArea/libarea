<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/admin', lang('Admin'), '/admin/badges', lang('Badges'), $data['meta_title']); ?>

        <div class="box badges">
          <form action="/admin/badge/edit/<?= $badge['badge_id']; ?>" method="post">
            <?= csrf_field() ?>
            <div class="boxline max-width">
              <label for="post_title">Id</label>
              <?= $badge['badge_id']; ?>
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title">Title</label>
              <input class="form-input" minlength="4" type="text" name="badge_title" value="<?= $badge['badge_title']; ?>" required>
              <div class="box_h">4 - 25 <?= lang('characters'); ?></div>
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title">Icon</label>
              <textarea class="add" name="badge_icon" required><?= $badge['badge_icon']; ?></textarea>
              <div class="box_h"><?= lang('For example'); ?>: &lt;i title="<?= lang('Title'); ?>" class="icon-trophy"&gt;&lt;/i&gt; </div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_title">Tl</label>
              <input class="form-input" type="text" name="badge_tl" value="<?= $badge['badge_tl']; ?>" required>
              <div class="box_h"><?= lang('For'); ?> TL (0 <?= lang('by default'); ?>)</div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_title">Score</label>
              <input class="form-input" type="text" name="badge_score" value="<?= $badge['badge_score']; ?>" required>
              <div class="box_h"><?= lang('Reward Weight'); ?></div>
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title"><?= lang('Description'); ?></label>
              <textarea class="add" minlength="12" name="badge_description" required><?= $badge['badge_description']; ?></textarea>
              <div class="box_h">12 - 250 <?= lang('characters'); ?></div>
            </div>
            <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
          </form>
        </div>
      </div>
    </div>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>