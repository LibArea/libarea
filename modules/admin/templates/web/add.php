<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), '/admin/webs', lang('Domains'), $data['meta_title']); ?>

      <div class="telo space">
        <div class="box create">
          <form action="/admin/web/add" method="post">
            <?= csrf_field() ?>
            <div class="boxline max-width">
              <label class="form-label" for="post_title">URL</label>
              <input class="form-input" type="text" name="link_url" value="">
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title"><?= lang('Title'); ?></label>
              <input class="form-input" type="text" name="link_title" value="" required>
              <div class="box_h">24 - 250 <?= lang('characters'); ?> («Газета.Ru» — интернет-газета)</div>
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title"><?= lang('Description'); ?></label>
              <textarea name="link_content" required></textarea>
              <div class="box_h">24 - 1500 <?= lang('characters'); ?></div>
            </div>
            <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
          </form>
        </div>
      </div>
    </div>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>