<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/admin', lang('admin'), getUrlByName('admin.webs'), lang('domains'), lang('change the site') . ' | ' . $data['domain']['link_url_domain']); ?>

    <div class="box create">
        <form action="/admin/web/edit/<?= $data['domain']['link_id']; ?>" method="post">
          <?= csrf_field() ?>
          <div class="boxline max-w780">
            <label for="post_title">Id:</label>
            <?= $data['domain']['link_id']; ?>
          </div>
          <div class="boxline max-w780">
            <label for="post_title">Domain:</label>
            <input class="form-input" type="text" name="link_domain" value="<?= $data['domain']['link_url_domain']; ?>">
          </div>
          <div class="boxline max-w780">
            <label class="block" for="post_title">URL</label>
            <input class="form-input" type="text" name="link_url" value="<?= $data['domain']['link_url']; ?>">
          </div>
          <div class="boxline max-w780">
            <label class="block" for="post_title"><?= lang('status'); ?></label>
            <input class="form-input" type="text" name="link_status" value="<?= $data['domain']['link_status']; ?>">
          </div>
          <div class="boxline max-w780">
            <label class="block" for="post_title"><?= lang('title'); ?></label>
            <input class="form-input" type="text" name="link_title" value="<?= $data['domain']['link_title']; ?>" required>
            <div class="size-14 gray-light-2">24 - 250 <?= lang('characters'); ?> («Газета.Ru» — интернет-газета)</div>
          </div>
          <div class="boxline max-w780">
            <label class="block" for="post_title"><?= lang('description'); ?></label>
            <textarea name="link_content" rows="4" required><?= $data['domain']['link_content']; ?></textarea>
            <div class="size-14 gray-light-2">24 - 1500 <?= lang('characters'); ?></div>
          </div>
          <?= includeTemplate('/_block/form/select-content', ['type' => 'topic', 'data' => $data, 'action' => 'edit', 'title' => lang('topics')]); ?>
          <input type="hidden" name="link_id" value="<?= $data['domain']['link_id']; ?>">
          <input type="submit" class="button block br-rd-5 white" name="submit" value="<?= lang('edit'); ?>" />
        </form>
    </div>
  </div>
  <?= lang('info-url-edit'); ?>
</main>