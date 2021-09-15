<?php if ($data['space']['space_cover_art'] != 'space_cover_no.jpeg') { ?>
  <div class="space-cover-box" style="background-image: url(/uploads/spaces/cover/<?= $data['space']['space_cover_art']; ?>); background-position: 50% 50%;min-height: 300px;">
    <div class="wrap">
    <?php } else { ?>
      <div class="space-box" style="background:<?= $data['space']['space_color']; ?>;">
        <div class="p0 ml5">
        <?php } ?>

        <?php if (!$uid['user_id']) { ?>
          <a class="right" href="/login">
            <div class="focus-space yes-space mr10">+ <?= lang('Read'); ?></div>
          </a>
        <?php } else { ?>
          <?php if ($data['space']['space_id'] != 1) { ?>
            <?php if ($data['space']['space_user_id'] != $uid['user_id']) { ?>
              <div class="right">
                <?php if (is_array($data['signed'])) { ?>
                  <div data-id="<?= $data['space']['space_id']; ?>" data-type="space" class="focus-id focus-space no-space mr15">
                    <i class="icon-ok-outline middle"></i>
                    <span class="middle"><?= lang('Unsubscribe'); ?></span>
                  </div>
                <?php } else { ?>
                  <div data-id="<?= $data['space']['space_id']; ?>" data-type="space" class="focus-id focus-space yes-space mr15">
                    <i class="icon-plus middle"></i>
                    <span class="middle"><?= lang('Read'); ?></span>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>
          <?php } ?>
        <?php } ?>
        <div class="space-text white">
          <?= spase_logo_img($data['space']['space_img'], 'max', $data['space']['space_name'], 'space-box-img'); ?>
          <a title="<?= $data['space']['space_name']; ?>" href="<?= getUrlByName('space', ['slug' => $data['space']['space_slug']]); ?>">
            <h1 class="size-31 mt5 mr0 mb10 ml0 p0 white"><?= $data['space']['space_name']; ?></h1>
          </a>
          <div class="space-slug">
            s/<?= $data['space']['space_slug']; ?>
          </div>
        </div>
        </div>
      </div>