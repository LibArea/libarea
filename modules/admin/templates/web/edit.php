<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/admin', lang('Admin'), '/admin/webs', lang('Domains'), $data['meta_title']); ?>

        <div class="telo space">
          <div class="box create">
            <form action="/admin/web/edit/<?= $domain['link_id']; ?>" method="post">
              <?= csrf_field() ?>
              <div class="boxline max-width">
                <label for="post_title">Id:</label>
                <?= $domain['link_id']; ?>
              </div>
              <div class="boxline max-width">
                <label for="post_title">Domain:</label>
                <input class="form-input" type="text" name="link_domain" value="<?= $domain['link_url_domain']; ?>">
              </div>
              <div class="boxline max-width">
                <label class="form-label" for="post_title">URL</label>
                <input class="form-input" type="text" name="link_url" value="<?= $domain['link_url']; ?>">
              </div>
              <div class="boxline max-width">
                <label class="form-label" for="post_title"><?= lang('Status'); ?></label>
                <input class="form-input" type="text" name="link_status" value="<?= $domain['link_status']; ?>">
              </div>
              <div class="boxline max-width">
                <label class="form-label" for="post_title"><?= lang('Title'); ?></label>
                <input class="form-input" type="text" name="link_title" value="<?= $domain['link_title']; ?>" required>
                <div class="box_h">24 - 250 <?= lang('characters'); ?> («Газета.Ru» — интернет-газета)</div>
              </div>
              <div class="boxline max-width">
                <label class="form-label" for="post_title"><?= lang('Description'); ?></label>
                <textarea name="link_content" required><?= $domain['link_content']; ?></textarea>
                <div class="box_h">24 - 1500 <?= lang('characters'); ?></div>
              </div>
              <input type="hidden" name="link_id" value="<?= $domain['link_id']; ?>">
              <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
            </form>
          </div>
        </div>
      </div>
    </div>

    <?= lang('info-url-edit'); ?>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>