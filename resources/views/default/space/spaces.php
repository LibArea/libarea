<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb15 pl15">
      <?php if ($uid['user_id'] > 0) { ?>
        <?php if ($data['add_space_button'] === true) { ?>
          <a title="<?= lang('To create'); ?>" class="right mt5 mb15 size-21" href="/space/add">
            <i class="icon-plus red"></i>
          </a>
        <?php } ?>
      <?php } ?>
      <h1><?= $data['h1']; ?></h1>

      <?php
      $pages = array(
        array('id' => 'spaces', 'url' => '/spaces', 'content' => lang('All')),
        array('id' => 'my-space', 'url' => '/space/my', 'content' => lang('Signed'), 'auth' => 'yes'),
      );
      echo tabs_nav($pages, $data['sheet'], $uid);
      ?>

      <?php if (!empty($data['spaces'])) { ?>
        <div class="flex all-box-spaces">
          <?php foreach ($data['spaces'] as  $sp) { ?>
            <?php if ($sp['space_cover_art'] != 'space_cover_no.jpeg') { ?>
              <div class="fons">
                <div class="space-info-box" style="background-image: url(/uploads/spaces/cover/small/<?= $sp['space_cover_art']; ?>)">

                <?php } else { ?>
                  <div class="fon">
                    <div class="space-info-box" style="background:<?= $sp['space_color']; ?>;">

                    <?php } ?>
                    <?php if ($sp['space_id'] != 1) { ?>
                      <span class="white absolute right-10">+ <?= $sp['space_focus_count'] ?></span>
                    <?php } ?>

                    <a title="<?= $sp['space_name']; ?>" class="absolute" href="/s/<?= $sp['space_slug']; ?>">
                      <?= spase_logo_img($sp['space_img'], 'max', $sp['space_name'], 'ava-54'); ?>
                    </a>

                    <span class="space-name">
                      <a title="<?= $sp['space_name']; ?>" class="space-s white absolute size-21" href="/s/<?= $sp['space_slug']; ?>">
                        <span class="space-name"> <?= $sp['space_name']; ?></span>
                      </a>
                    </span>

                    <?php if (!$uid['user_id']) { ?>
                      <div class="top-15-px">
                        <a href="/login">
                          <div class="focus-space yes-space absolute">
                            <i class="icon-plus middle"></i>
                            <span class="middle"><?= lang('Read'); ?></span>
                          </div>
                        </a>
                      </div>
                    <?php } else { ?>
                      <?php if ($sp['space_id'] != 1) { ?>
                        <?php if ($sp['space_user_id'] != $uid['user_id']) { ?>
                          <div class="top-15-px">
                            <?php if ($sp['signed_space_id'] >= 1) { ?>
                              <div data-id="<?= $sp['space_id']; ?>" data-type="space" class="focus-id focus-space absolute">
                                <i class="icon-ok-outline middle"></i>
                                <span class="middle"><?= lang('Unsubscribe'); ?></span>
                              </div>
                            <?php } else { ?>
                              <div data-id="<?= $sp['space_id']; ?>" data-type="space" class="focus-id focus-space absolute">
                                <i class="icon-plus middle"></i>
                                <span class="middle"><?= lang('Read'); ?></span>
                              </div>
                            <?php } ?>
                          </div>
                        <?php } ?>
                        <?php if ($sp['space_user_id'] == $uid['user_id']) { ?>
                          <div class="focus-space absolute">
                            <i class="icon-ok-outline middle"></i>
                            <span class="middle"><?= lang('Created by'); ?></span>
                          </div>
                        <?php } ?>
                      <?php } ?>
                    <?php } ?>
                    </div>
                  </div>
                <?php } ?>
                </div>
              <?php } else { ?>
                <?= no_content('No spaces'); ?>
              <?php } ?>

              </div>
              <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/spaces'); ?>
  </main>
  <aside>
    <div class="white-box p15">
      <?php if ($data['sheet'] == 'spaces') { ?>
        <?= lang('info_space'); ?>
      <?php } else { ?>
        <?= lang('my_info_space'); ?>
      <?php } ?>
    </div>
  </aside>
</div>