<?php if ($data['writers']) { ?>
  <div class="bg-white br-rd5 br-box-gray p15">
    <?php foreach ($data['writers'] as  $writer) { ?>
      <div class="flex br-bottom">
        <div class="mr15 mt10">
          <?= $writer['sum']; ?>
          <span class="block gray lowercase"><?= Translate::get('views'); ?></span>
        </div>
        <div class="p15">
          <?= user_avatar_img($writer['avatar'], 'max', $writer['login'], 'w50 h50'); ?>
        </div>
        <div class="mt10">
          <a href="<?= getUrlByName('profile', ['login' => $writer['login']]); ?>"><?= $writer['login']; ?></a>
          <div class="mr13 gray-600 mr15">
            <?php if ($writer['about']) { ?>
              <?= $writer['about']; ?>
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