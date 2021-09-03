<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/spaces', lang('Spaces'), lang('Add Space')); ?>

      <div class="max-width space">
        <div class="box create">
          <form action="/space/create" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="boxline">
              <label class="form-label" for="post_title">URL (slug)<sup class="red">*</sup></label>
              <input class="form-input" minlength="3" maxlength="12" type="text" name="space_slug" />
              <div class="box_h gray">3 - 12 <?= lang('characters'); ?> (<?= lang('english'); ?>)</div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_title"><?= lang('Title'); ?><sup class="red">*</sup></label>
              <input class="form-input" minlength="4" maxlength="18" type="text" name="space_name" />
              <div class="box_h gray">4 - 18 <?= lang('characters'); ?></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Publications'); ?></label>
              <input type="radio" name="permit" checked value="0"> <?= lang('All'); ?>
              <input type="radio" name="permit" value="1"> <?= lang('Just me'); ?>
              <div class="box_h gray"<?= lang('Who will be able to post'); ?></div>
            </div>
            <div class="boxline">
              <label class="form-label" for="post_content"><?= lang('Show'); ?></label>
              <input type="radio" name="feed" checked value="0"> <?= lang('Yes'); ?>
              <input type="radio" name="feed" value="1"> <?= lang('No'); ?>
              <div class="box_h gray"><?= lang('Posts will not be visible in the feed'); ?></div>
            </div>
            <div class="boxline">
              <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
            </div>
          </form>
          <div class="boxline">
            <?= lang('You can add spaces'); ?>: <b><?= $data['num_add_space']; ?></b>
          </div>
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