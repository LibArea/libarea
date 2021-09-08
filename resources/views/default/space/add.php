<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), '/spaces', lang('Spaces'), lang('Add Space')); ?>

    <div class="max-width space">
      <div class="box create">
        <form action="/space/create" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>

          <?php field_input(array(
            array('title' => 'URL (slug)', 'type' => 'text', 'name' => 'space_slug', 'value' => '', 'min' => 3, 'max' => 12, 'help' => '3 - 12 ' . lang('characters') . ' (' . lang('english') . ')'),
            array('title' => lang('Title'), 'type' => 'text', 'name' => 'space_name', 'value' => '', 'min' => 4, 'max' => 18, 'help' => '4 - 18 ' . lang('characters')),
          )); ?>

          <?php field_radio(array(
            array('title' => lang('Publications'), 'name' => 'permit', 'checked' => 0, 'help' => lang('Who will be able to post')),
            array('title' => lang('To close?'), 'name' => 'feed', 'checked' => 0, 'help' => lang('Posts will not be visible in the feed')),
          )); ?>

          <div class="boxline">
            <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
          </div>
        </form>
        <div class="boxline">
          <?= lang('You can add spaces'); ?>: <b><?= $data['num_add_space']; ?></b>
        </div>
      </div>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('Under development'); ?>...
    </div>
  </aside>
</div>