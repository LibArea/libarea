<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 mb15">
    <p class="hidden ml15 size-18"><?= $data['h1']; ?>

      <?php if ($uid['user_id'] > 0) { ?>
        <?php if ($data['add_space_button'] === true) { ?>
          <a title="<?= lang('to create'); ?>" class="right mt5 ml15 size-18" href="/space/add">
            <i class="icon-plus red"></i>
          </a>
        <?php } ?>
      <?php } ?>

    </p>
    <?php
    $pages = array(
      array('id' => 'spaces', 'url' => '/spaces', 'content' => lang('all'), 'icon' => 'icon-air'),
      array('id' => 'my-space', 'url' => '/space/my', 'content' => lang('signed'), 'auth' => 'yes', 'icon' => 'icon-air'),
    );
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <div class="pt5 bg-white border-box-1">
    <?php if (!empty($data['spaces'])) { ?>
      <div class="flex flex-row flex-wrap grid-cols-2 mb10">
        <?php foreach ($data['spaces'] as  $sp) { ?>
          <div class="w-50 mb-w-100 mb10 flex flex-row">
            <?php if ($sp['space_cover_art'] != 'space_cover_no.jpeg') { ?>
              <div class="h120 p10 w-100 m10" style="background-image: url(/uploads/spaces/cover/small/<?= $sp['space_cover_art']; ?>)">
              <?php } else { ?>
                <div class="h120 p10 w-100 m10" style="background:<?= $sp['space_color']; ?>;">
                <?php } ?>

                <?php if ($sp['space_id'] != 1) { ?>
                  <span class="white right">+ <?= $sp['space_focus_count'] ?></span>
                <?php } ?>
                <a title="<?= $sp['space_name']; ?>" class="" href="<?= getUrlByName('space', ['slug' => $sp['space_slug']]); ?>">
                  <?= spase_logo_img($sp['space_img'], 'max', $sp['space_name'], 'w54 mr10'); ?>
                </a>
                <span class="space-name">
                  <a title="<?= $sp['space_name']; ?>" class="white size-21" href="<?= getUrlByName('space', ['slug' => $sp['space_slug']]); ?>">
                    <span class="space-name"> <?= $sp['space_name']; ?></span>
                  </a>
                </span>
                <?php if (!$uid['user_id']) { ?>
                  <div class="mt10">
                    <a href="<?= getUrlByName('login'); ?>">
                      <div class="focus-space yes-space ">
                        <i class="icon-plus middle"></i>
                        <span class="middle"><?= lang('read'); ?></span>
                      </div>
                    </a>
                  </div>
                <?php } else { ?>
                  <?php if ($sp['space_id'] != 1) { ?>
                    <?php if ($sp['space_user_id'] != $uid['user_id']) { ?>
                      <div class="mt15">
                        <?php if ($sp['signed_space_id'] >= 1) { ?>
                          <div data-id="<?= $sp['space_id']; ?>" data-type="space" class="focus-id focus-space ">
                            <i class="icon-ok-outline middle"></i>
                            <span class="middle"><?= lang('unsubscribe'); ?></span>
                          </div>
                        <?php } else { ?>
                          <div data-id="<?= $sp['space_id']; ?>" data-type="space" class="focus-id focus-space ">
                            <i class="icon-plus middle"></i>
                            <span class="middle"><?= lang('read'); ?></span>
                          </div>
                        <?php } ?>
                      </div>
                    <?php } ?>
                    <?php if ($sp['space_user_id'] == $uid['user_id']) { ?>
                      <div class="focus-space mt15">
                        <i class="icon-ok-outline middle"></i>
                        <span class="middle"><?= lang('created by'); ?></span>
                      </div>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>


                </div>
              </div>
            <?php } ?>
          </div>
        <?php } else { ?>
          <?= includeTemplate('/_block/no-content', ['lang' => 'no spaces']); ?>
        <?php } ?>

        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/spaces'); ?>
</main>
<?php if ($data['sheet'] == 'spaces') { ?>
  <?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-space')]); ?>
<?php } else { ?>
  <?= includeTemplate('/_block/aside-lang', ['lang' => lang('my-info-space')]); ?>
<?php } ?>