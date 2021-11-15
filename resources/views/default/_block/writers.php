<?php if ($data['writers']) { ?>
  <div class="bg-white br-rd5 br-box-gray p15">
    <?php foreach ($data['writers'] as  $writer) { ?>
      <div class="flex br-bottom">
        <div class="mr15 mt10">
          <?= $writer['sum']; ?>
          <span class="block size-15 gray lowercase"><?= Translate::get('views'); ?></span>
        </div>
        <div class="p15">
          <?= user_avatar_img($writer['user_avatar'], 'max', $writer['user_login'], 'w54'); ?>
        </div>
        <div class="mt10">
          <a href="<?= getUrlByName('user', ['login' => $writer['user_login']]); ?>"><?= $writer['user_login']; ?></a>
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
<?php } else { ?>
  <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
<?php } ?>