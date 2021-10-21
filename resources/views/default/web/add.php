<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/webs', lang('sites'), getUrlByName('webs'), lang('domains'), lang('add a website')); ?>

    <div class="box create">
      <form action="/web/create" method="post">
        <?= csrf_field() ?>

        <?= includeTemplate('/_block/form/field-input', ['data' => [
          ['title' => lang('URL'), 'type' => 'text', 'name' => 'link_url', 'value' => ''],
          ['title' => lang('title'), 'type' => 'text', 'name' => 'link_title', 'value' => '', 'help' => '24 - 250 ' . lang('characters') . ' («Газета.Ru» — интернет-газета)'],
        ]]); ?>

        <?php includeTemplate('/_block/editor/textarea', ['title' => lang('description'), 'type' => 'text', 'name' => 'link_content', 'content' => '', 'min' => 24, 'max' => 1500, 'help' => '24 - 1500 ' . lang('characters')]); ?>

        <?= includeTemplate('/_block/form/select-content', ['type' => 'topic', 'data' => $data, 'action' => 'add', 'title' => lang('topics')]); ?>
        <input type="submit" class="button block br-rd5 white" name="submit" value="<?= lang('add'); ?>" />
      </form>
    </div>
  </div>
</main>