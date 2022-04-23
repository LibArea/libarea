<?php if (UserData::checkAdmin()) : ?>
    <li>
      <a href="<?= getUrlByName('web.audits'); ?>">
        <i class="bi bi-exclamation-diamond"></i>
        <?= __('audits'); ?>
         <?php if (!empty($data['audit_count'])) : ?><span class="red ml5">(<?= $data['audit_count']; ?>)</span><?php endif; ?>
      </a>
    </li>
<?php endif; ?>

<li>
  <a href="<?= getUrlByName('web.user.sites'); ?>">
    <i class="bi-plus-lg"></i>
    <?= __('web.user.sites.view'); ?>
    <?php if ($data['user_count_site'] != 0) : ?>
      (<?= $data['user_count_site']; ?>)
    <?php endif; ?>
  </a>
</li>
<?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_site')) : ?>
  <?php if (Config::get('trust-levels.count_add_site') > $data['user_count_site']) : ?>
    <li>
      <a href="<?= getUrlByName('web.add'); ?>">
        <i class="bi-plus-lg"></i>
          <?= __('add.option', ['name' => __('website')]); ?>
      </a>
    </li>
  <?php endif; ?>
<?php endif; ?>
