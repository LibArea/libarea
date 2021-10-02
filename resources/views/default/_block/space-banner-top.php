<?php if ($data['space']['space_cover_art'] != 'space_cover_no.jpeg') { ?>
  <div class="pt160 relative" style="background-image: url(/uploads/spaces/cover/<?= $data['space']['space_cover_art']; ?>); background-position: 50% 50%;min-height: 300px;">

  <?php } else { ?>
    <div class="relative pt20" style="background:<?= $data['space']['space_color']; ?>;height: 150px;">
    <?php } ?>

    <?php if (!$uid['user_id']) { ?>
      <a class="right" href="<?= getUrlByName('login'); ?>">
        <div class="focus-space size-18 yes-space mt20 mr10">+ <?= lang('read'); ?></div>
      </a>
    <?php } else { ?>
      <?php if ($data['space']['space_id'] != 1) { ?>
        <?php if ($data['space']['space_user_id'] != $uid['user_id']) { ?>
          <div class="right">
            <?php if (is_array($data['signed'])) { ?>
              <div data-id="<?= $data['space']['space_id']; ?>" data-type="space" class="focus-id focus-space no-space mr15">
                <i class="bi bi-check middle"></i>
                <span class="middle"><?= lang('unsubscribe'); ?></span>
              </div>
            <?php } else { ?>
              <div data-id="<?= $data['space']['space_id']; ?>" data-type="space" class="focus-id focus-space yes-space mr15">
                <i class="bi bi-plus middle"></i>
                <span class="middle"><?= lang('read'); ?></span>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>
    <?php } ?>
    <div class="flex flex-row gap-4 flex-auto">
      <?= spase_logo_img($data['space']['space_img'], 'max', $data['space']['space_name'], 'mr10 ml10 w110'); ?>
      <a class="white" title="<?= $data['space']['space_name']; ?>" href="<?= getUrlByName('space', ['slug' => $data['space']['space_slug']]); ?>">
        <h1 class="size-31 mt5 mr0 mb10 ml0 p0 "><?= $data['space']['space_name']; ?></h1>

        s/<?= $data['space']['space_slug']; ?>
      </a>

    </div>
    </div>