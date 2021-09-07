<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), '/s/' . $data['space']['space_slug'], $data['space']['space_name'], lang('Change') . ' — ' . $data['space']['space_slug']); ?>
    <?php includeTemplate('/_block/space-nav', ['data' => $data, 'uid' => $uid]); ?>

    <div class="telo space">
      <div class="box create">
        <form action="/space/edit" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>

          <?php field_input(array(
            array('title' => 'URL (slug)', 'type' => 'text', 'name' => 'space_slug', 'value' => $data['space']['space_slug'], 'min' => 3, 'max' => 12, 'help' => '3 - 12 ' . lang('characters') . ' (' . lang('english') . ')'),
            array('title' => lang('Title'), 'type' => 'text', 'name' => 'space_name', 'value' => $data['space']['space_name'], 'min' => 4, 'max' => 18, 'help' => '4 - 18 ' . lang('characters') . ' (' . lang('english') . ')'),
            array('title' => lang('Long'), 'type' => 'text', 'name' => 'space_short_text', 'value' => $data['space']['space_short_text'], 'min' => 10, 'max' => 250, 'help' => lang('Long name from') . ' 10 - 250 ' . lang('characters')),
            array('title' => 'Meta-', 'type' => 'text', 'name' => 'space_description', 'value' => $data['space']['space_description'], 'min' => 60, 'max' => 180, 'help' => 'Description: 60 - 180 ' . lang('characters')),
          )); ?>

          <?php field_radio(array(
            array('title' => lang('Publications'), 'name' => 'permit', 'checked' => $data['space']['space_permit_users'], 'help' => lang('Who will be able to post')),
            array('title' => lang('To close?'), 'name' => 'feed', 'checked' => $data['space']['space_feed'], 'help' => lang('Posts will not be visible in the feed')),
          )); ?>

          <div id="box" class="boxline">
            <label class="form-label" for="post_content"><?= lang('Color'); ?></label>
            <input class="form-input" type="color" value="<?= $data['space']['space_color']; ?>" id="colorSpace">
            <input type="hidden" name="color" value="" id="color">
          </div>
          <div class="boxline">
            <label class="form-label" for="post_content"><?= lang('Text'); ?> (Sidebar)</label>
            <textarea rows="6" cols="60" name="space_text"><?= $data['space']['space_text']; ?></textarea>
            <div class="box_h gray">Markdown</div>
          </div>
          <div class="boxline">
            <input type="hidden" name="space_id" id="space_id" value="<?= $data['space']['space_id']; ?>">
            <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
          </div>
        </form>
      </div>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info-space-edit'); ?>
    </div>
  </aside>
</div>