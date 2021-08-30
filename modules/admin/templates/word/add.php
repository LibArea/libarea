<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), '/admin/words', lang('Stop words'), $data['meta_title']); ?>

      <div class="box badges">
        <form action="/admin/word/add" method="post">
          <?= csrf_field() ?>
          <div class="boxline max-width">
            <label class="form-label" or="post_title"><?= lang('Stop word'); ?></label>
            <input type="text" class="form-input" name="word">
          </div>
          <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
        </form>
      </div>
    </div>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>