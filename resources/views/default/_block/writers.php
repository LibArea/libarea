<?php if ($data['writers']) { ?>
  <div class="box-white">
    <?php foreach ($data['writers'] as  $writer) { ?>
      <div class="flex br-bottom">
        <div class="mr15 mt10">
          <?= $writer['sum']; ?>
          <span class="block gray lowercase"><?= __('views'); ?></span>
        </div>
        <div class="p15">
          <?= Html::image($writer['avatar'], $writer['login'], 'w50 h50', 'avatar', 'small'); ?>
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
  <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
<?php } ?>