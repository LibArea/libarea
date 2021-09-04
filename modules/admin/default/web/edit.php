<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), '/admin/webs', lang('Domains'), lang('Change the site') . ' | ' . $data['domain']['link_url_domain']); ?>

      <div class="telo space">
        <div class="box create">
          <form action="/admin/web/edit/<?= $data['domain']['link_id']; ?>" method="post">
            <?= csrf_field() ?>
            <div class="boxline max-width">
              <label for="post_title">Id:</label>
              <?= $data['domain']['link_id']; ?>
            </div>
            <div class="boxline max-width">
              <label for="post_title">Domain:</label>
              <input class="form-input" type="text" name="link_domain" value="<?= $data['domain']['link_url_domain']; ?>">
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title">URL</label>
              <input class="form-input" type="text" name="link_url" value="<?= $data['domain']['link_url']; ?>">
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title"><?= lang('Status'); ?></label>
              <input class="form-input" type="text" name="link_status" value="<?= $data['domain']['link_status']; ?>">
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title"><?= lang('Title'); ?></label>
              <input class="form-input" type="text" name="link_title" value="<?= $data['domain']['link_title']; ?>" required>
              <div class="box_h">24 - 250 <?= lang('characters'); ?> («Газета.Ru» — интернет-газета)</div>
            </div>
            <div class="boxline max-width">
              <label class="form-label" for="post_title"><?= lang('Description'); ?></label>
              <textarea name="link_content" rows="4" required><?= $data['domain']['link_content']; ?></textarea>
              <div class="box_h">24 - 1500 <?= lang('characters'); ?></div>
            </div>
            <input type="hidden" name="link_id" value="<?= $data['domain']['link_id']; ?>">
            <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
          </form>
        </div>
      </div>
    </div>
    <?= lang('info-url-edit'); ?>
  </main>
</div>