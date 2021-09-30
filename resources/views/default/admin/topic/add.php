<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), '/admin/topics', lang('topics'), lang('add topic')); ?>

  <div class="space">
    <div class="box create">
      <form action="/admin/topic/add" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?= includeTemplate('/_block/form/field-input', ['data' => [
          ['title' => lang('title'), 'type' => 'text', 'name' => 'topic_title', 'value' => '', 'min' => 3, 'max' => 64, 'help' => '3 - 64 ' . lang('characters')],
          ['title' => lang('title') . ' (SEO)', 'type' => 'text', 'name' => 'topic_seo_title', 'value' => '', 'min' => 4, 'max' => 225, 'help' => '4 - 225 ' . lang('characters')],
          ['title' => lang('Slug'), 'type' => 'text', 'name' => 'topic_slug', 'value' => '', 'min' => 3, 'max' => 32, 'help' => '3 - 32 ' . lang('characters') . ' (a-zA-Z0-9)'],
        ]]); ?>

        <div class="boxline">
          <label for="post_content">
            <?= lang('meta description'); ?><sup class="red">*</sup>
          </label>
          <textarea rows="6" class="add" minlength="44" name="topic_description"></textarea>
          <div class="size-14 gray-light-2">> 44 <?= lang('characters'); ?></div>
        </div>
        <input type="submit" name="submit" class="button block br-rd-5 white" value="<?= lang('add'); ?>" />
      </form>
    </div>
  </div>
</main>