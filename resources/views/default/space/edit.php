<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/s/' . $data['space']['space_slug'], $data['space']['space_name'], lang('Change') . ' — ' . $data['space']['space_slug']); ?>

      <ul class="nav-tabs list-none mt0 pt10 pr15 pb15 pl0">
        <li class="active">
          <span><?= lang('Edit'); ?></span>
        </li>
        <li>
          <a href="/space/logo/<?= $data['space']['space_slug']; ?>/edit">
            <span><?= lang('Logo'); ?> / <?= lang('Cover art'); ?></span>
          </a>
        </li>
      </ul>
      <div class="telo space">
        <div class="box create">
          <form action="/space/edit" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="boxline">
              <label class="form-label" for="post_title">URL<sup class="red">*</sup></label>
              <input class="form-input" minlength="3" maxlength="12" type="text" value="<?= $data['space']['space_slug']; ?>" name="space_slug" />
              <div class="box_h gray">3 - 12 <?= lang('characters'); ?></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_title"><?= lang('Title'); ?><sup class="red">*</sup></label>
              <input class="form-input" minlength="4" maxlength="18" type="text" value="<?= $data['space']['space_name']; ?>" name="space_name" />
              <div class="box_h gray">4 - 18 <?= lang('characters'); ?></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Long'); ?><sup class="red">*</sup></label>
              <input class="form-input" minlength="10" maxlength="250" type="text" name="space_short_text" value="<?= $data['space']['space_short_text']; ?>">
              <div class="box_h gray"><?= lang('Long name from'); ?> 10 - 250 <?= lang('characters'); ?></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Publications'); ?><sup class="red">*</sup></label>
              <input type="radio" name="permit" <?php if ($data['space']['space_permit_users'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('All'); ?>
              <input type="radio" name="permit" <?php if ($data['space']['space_permit_users'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Just me'); ?>
              <div class="box_h gray"><?= lang('Who will be able to post posts'); ?></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Show'); ?><sup class="red">*</sup></label>
              <input type="radio" name="feed" <?php if ($data['space']['space_feed'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('Yes'); ?>
              <input type="radio" name="feed" <?php if ($data['space']['space_feed'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('No'); ?>
              <div class="box_h gray"><?= lang('Posts will not be visible in the feed'); ?></b></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content">Meta-<sup class="red">*</sup></label>
              <input class="form-input" minlength="60" type="text" name="space_description" value="<?= $data['space']['space_description']; ?>">
              <div class="box_h gray">Description: 60 - 180 <?= lang('characters'); ?></div>
            </div>
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
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info_space_edit'); ?>
    </div>
  </aside>
</div>