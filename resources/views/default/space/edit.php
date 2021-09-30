<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <?= breadcrumb('/', lang('home'), '/s/' . $data['space']['space_slug'], $data['space']['space_name'], lang('change') . ' — ' . $data['space']['space_slug']); ?>

  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <?= includeTemplate('/_block/space-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <form action="/space/edit" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <?= includeTemplate('/_block/form/field-input', ['data' => [
      ['title' => 'URL (slug)', 'type' => 'text', 'name' => 'space_slug', 'value' => $data['space']['space_slug'], 'min' => 3, 'max' => 12, 'help' => '3 - 12 ' . lang('characters') . ' (' . lang('english') . ')'],
      ['title' => lang('title'), 'type' => 'text', 'name' => 'space_name', 'value' => $data['space']['space_name'], 'min' => 4, 'max' => 18, 'help' => '4 - 18 ' . lang('characters') . ' (' . lang('english') . ')'],
      ['title' => lang('long'), 'type' => 'text', 'name' => 'space_short_text', 'value' => $data['space']['space_short_text'], 'min' => 10, 'max' => 250, 'help' => lang('long name from') . ' 10 - 250 ' . lang('characters')],
      ['title' => 'Meta-', 'type' => 'text', 'name' => 'space_description', 'value' => $data['space']['space_description'], 'min' => 60, 'max' => 180, 'help' => 'Description: 60 - 180 ' . lang('characters')],
    ]]); ?>

    <?= includeTemplate('/_block/form/field-radio', ['data' => [
      ['title' => lang('publications'), 'name' => 'permit', 'checked' => $data['space']['space_permit_users'], 'help' => lang('who will be able to post')],
      ['title' => lang('to close?'), 'name' => 'feed', 'checked' => $data['space']['space_feed'], 'help' => lang('posts will not be visible in the feed')],
    ]]); ?>

    <div id="box" class="boxline">
      <label class="form-label" for="post_content"><?= lang('color'); ?></label>
      <input class="form-input" type="color" value="<?= $data['space']['space_color']; ?>" id="colorSpace">
      <input type="hidden" name="color" value="" id="color">
    </div>

    <?php includeTemplate('/_block/editor/textarea', ['title' => lang('text') . ' (Sidebar)', 'type' => 'text', 'name' => 'space_text', 'content' => $data['space']['space_text'], 'help' => lang('markdown')]); ?>

    <div class="boxline">
      <input type="hidden" name="space_id" id="space_id" value="<?= $data['space']['space_id']; ?>">
      <input type="submit" class="button white br-rd-5" name="submit" value="<?= lang('edit'); ?>" />
    </div>
  </form>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-space-edit')]); ?>