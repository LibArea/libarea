<?php if (UserData::checkAdmin()) : ?>
    <li>
      <a href="<?= url('web.audits'); ?>">
        <i class="bi bi-exclamation-diamond"></i>
        <?= __('audits'); ?>
         <?php if (!empty($data['audit_count'])) : ?><span class="red ml5">(<?= $data['audit_count']; ?>)</span><?php endif; ?>
      </a>
    </li>
<?php endif; ?>

<li>
  <a href="<?= url('web.user.sites'); ?>">
    <i class="bi-plus-lg"></i>
    <?= __('web.user.sites.view'); ?>
    <?php if ($data['user_count_site'] != 0) : ?>
      (<?= $data['user_count_site']; ?>)
    <?php endif; ?>
  </a>
</li>
<?php if (UserData::getRegType(config('trust-levels.tl_add_site'))) : ?>
  <?php if (config('trust-levels.count_add_site') > $data['user_count_site']) : ?>
    <li>
      <a href="<?= url('web.add'); ?>">
        <i class="bi-plus-lg"></i>
          <?= __('add.option', ['name' => __('website')]); ?>
      </a>
    </li>
  <?php endif; ?>
<?php endif; ?>
