<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/admin', lang('admin'), getUrlByName('admin.webs'), lang('domains'), lang('add a website')); ?>

    <div class="space">
      <div class="box create">
        <form action="/admin/web/add" method="post">
          <?= csrf_field() ?>
          <div class="boxline max-w780">
            <label class="form-label" for="post_title">URL</label>
            <input class="form-input" type="text" name="link_url" value="">
          </div>
          <div class="boxline max-w780">
            <label class="form-label" for="post_title"><?= lang('title'); ?></label>
            <input class="form-input" type="text" name="link_title" value="" required>
            <div class="size-14 gray-light-2">24 - 250 <?= lang('characters'); ?> («Газета.Ru» — интернет-газета)</div>
          </div>
          <div class="boxline max-w780">
            <label class="form-label" for="post_title"><?= lang('description'); ?></label>
            <textarea rows="4" name="link_content" required></textarea>
            <div class="size-14 gray-light-2">24 - 1500 <?= lang('characters'); ?></div>
          </div>       
          <?= includeTemplate('/_block/form/select-content', ['type' => 'topic', 'data' => $data, 'action' => 'add', 'title' => lang('topics')]); ?>
          <input type="submit" class="button block br-rd-5 white" name="submit" value="<?= lang('add'); ?>" />
        </form>
      </div>
    </div>
  </div>
</main>