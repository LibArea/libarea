<li>
  <a href="<?= getUrlByName('web.user.sites'); ?>">
    <i class="bi-plus-lg"></i>
    <?= __('web.user.sites.view'); ?>
    <?php if ($data['user_count_site'] != 0) { ?>
      (<?= $data['user_count_site']; ?>)
    <?php } ?>
  </a>
</li>
<?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_site')) { ?>
  <?php if (Config::get('trust-levels.count_add_site') > $data['user_count_site']) { ?>
    <li>
      <a href="<?= getUrlByName('web.add'); ?>">
        <i class="bi-plus-lg"></i>
        <?= sprintf(__('add.option'), mb_strtolower(__('website'))); ?>
      </a>
    </li>
  <?php } ?>
<?php } ?>