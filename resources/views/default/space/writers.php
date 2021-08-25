<?php if ($writers) { ?>
  <div class="white-box">
    <div class="p15">
      <?php foreach ($writers as  $writer) { ?>
        <div class="flex border-bottom">
          <div class="mr15">
            <?= $writer['sum']; ?>
            <span class="block size-15 gray lowercase"><?= lang('views-n'); ?></span>
          </div>
          <div class="p15">
            <?= user_avatar_img($writer['user_avatar'], 'max', $writer['user_login'], 'ava-54'); ?>
          </div>
          <div class="">
            <a href="/u/<?= $writer['user_login']; ?>"><?= $writer['user_login']; ?></a>
            <div class="mr13 gray-light size-15 mr15">
              <?php if ($writer['user_about']) { ?>
                <?= $writer['user_about']; ?>
              <?php } else { ?>
                ...
              <?php } ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
<?php } else { ?>
  <?= no_content('No'); ?>
<?php } ?>