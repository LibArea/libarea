<li>
  <a <?= is_current(url('web.user.sites')) ? 'class="active"' : ''; ?> href="<?= url('web.user.sites'); ?>">
    <i class="bi-plus-lg"></i> <?= __('web.my_website'); ?>
    <?php if ($data['user_count_site'] != 0) : ?>
      (<?= $data['user_count_site']; ?>)
    <?php endif; ?>
  </a>
</li>

<?php if (Access::trustLevels(config('trust-levels.tl_add_item'))) : ?>
  <?php if ($data['user_count_site'] == 0 || UserData::checkAdmin()) : ?>
    <li>
      <a href="<?= url('web.add'); ?>">
        <i class="bi-plus-lg"></i> <?= __('web.add_website'); ?>
      </a>
    </li>
  <?php endif; ?>
<?php endif; ?>

<li>
  <a <?= is_current(url('web.bookmarks')) ? 'class="active"' : ''; ?> href="<?= url('web.bookmarks'); ?>">
    <i class="bi-bookmark"></i> <?= __('web.bookmarks'); ?>
  </a>
</li>

<?php if (UserData::checkAdmin()) : ?>
  <li class="uppercase-box mt15"><?= __('admin.home'); ?></li>

  <li>
    <a href="<?= url('content.add', ['type' => 'category']); ?>">
      <i class="bi-plus-lg"></i> <?= __('web.add_category'); ?>
    </a>
  </li>

  <li>
    <a <?= is_current(url('web.audits')) ? 'class="active"' : ''; ?> href="<?= url('web.audits'); ?>">
      <i class="bi bi-exclamation-diamond"></i> <?= __('web.audits'); ?>
      <?php if (!empty($data['audit_count'])) : ?><span class="red ml5">(<?= $data['audit_count']; ?>)</span><?php endif; ?>
    </a>
  </li>

  <li>
    <a <?= is_current(url('web.deleted')) ? 'class="active"' : ''; ?> href="<?= url('web.deleted'); ?>">
      <i class="bi-circle"></i> <?= __('web.deleted'); ?>
    </a>
  </li>

  <li>
    <a href="<?= url('admin.facets.type', ['type' => 'category']); ?>">
      <i class="bi-columns-gap"></i> <?= __('web.facets'); ?>
    </a>
  </li>
<?php endif; ?>