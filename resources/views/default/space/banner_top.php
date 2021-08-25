<?php if ($space['space_cover_art'] != 'space_cover_no.jpeg') { ?>
  <div class="space-cover-box" style="background-image: url(/uploads/spaces/cover/<?= $space['space_cover_art']; ?>); background-position: 50% 50%;min-height: 300px;">
    <div class="wrap">
    <?php } else { ?>
      <div class="space-box" style="background:<?= $space['space_color']; ?>;">
        <div class="p0 ml5">
        <?php } ?>

        <?php if (!$uid['user_id']) { ?>
          <div class="right">
            <a href="/login">
              <div class="focus-space yes-space">+ <?= lang('Read'); ?></div>
            </a>
          </div>
        <?php } else { ?>
          <?php if ($space['space_id'] != 1) { ?>
            <?php if ($space['space_user_id'] != $uid['user_id']) { ?>
              <div class="right">
                <?php if (is_array($space_signed)) { ?>
                  <div data-id="<?= $space['space_id']; ?>" data-type="space" class="focus-id focus-space no-space">
                    <i class="icon-ok-outline middle"></i>
                    <span class="middle"><?= lang('Unsubscribe'); ?></span>
                  </div>
                <?php } else { ?>
                  <div data-id="<?= $space['space_id']; ?>" data-type="space" class="focus-id focus-space yes-space">
                    <i class="icon-plus middle"></i>
                    <span class="middle"><?= lang('Read'); ?></span>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>
          <?php } ?>
        <?php } ?>
        <div class="space-text white">
          <?= spase_logo_img($space['space_img'], 'max', $space['space_name'], 'space-box-img'); ?>
          <a title="<?= $space['space_name']; ?>" href="/s/<?= $space['space_slug']; ?>">
            <h1 class="size-31 mt5 mr0 mb10 ml0 p0 white"><?= $space['space_name']; ?></h1>
          </a>
          <div class="space-slug">
            s/<?= $space['space_slug']; ?>
          </div>
        </div>
        </div>
      </div>