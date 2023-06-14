<?php 
$category = $data['category'] ?? false;
$url = $category ? url('content.add', ['type' => 'item']) . '/' . $category['facet_id'] : url('content.add', ['type' => 'item']);
$user_count_site = $data['user_count_site'] ?? false; 
?>

<li>
  <a <?= is_current(url('web.user.sites')) ? 'class="active"' : ''; ?> href="<?= url('web.user.sites'); ?>">
    <?= __('web.my_website'); ?>
    <?php if ($user_count_site != false) : ?>
      (<?= $user_count_site; ?>)
    <?php endif; ?>
  </a>
</li>

<?php if (Access::trustLevels(config('trust-levels.tl_add_item')) || ($user_count_site != false)) : ?>
  <li>
    <a href="<?= $url; ?>">
      <?= __('web.add_website'); ?>
    </a>
  </li>
<?php endif; ?>

<li>
  <a <?= is_current(url('web.bookmarks')) ? 'class="active"' : ''; ?> href="<?= url('web.bookmarks'); ?>">
    <?= __('web.bookmarks'); ?>
  </a>
</li>

<?php if (UserData::checkAdmin()) : ?>
  <li class="uppercase-box mt15"><?= __('admin.home'); ?></li>

  <li>
    <a href="<?= url('content.add', ['type' => 'category']); ?>">
      <?= __('web.add_category'); ?>
    </a>
  </li>

  <li>
    <a <?= is_current(url('web.audits')) ? 'class="active"' : ''; ?> href="<?= url('web.audits'); ?>">
      <?= __('web.audits'); ?>
      <?php if (!empty($data['audit_count'])) : ?><span class="red ml5">(<?= $data['audit_count']; ?>)</span><?php endif; ?>
    </a>
  </li>

  <li>
    <a <?= is_current(url('web.comments')) ? 'class="active"' : ''; ?> href="<?= url('web.comments'); ?>">
      <?= __('web.comments'); ?>
    </a>
  </li>

  <li>
    <a <?= is_current(url('web.deleted')) ? 'class="active"' : ''; ?> href="<?= url('web.deleted'); ?>">
      <?= __('web.deleted'); ?>
    </a>
  </li>

  <li>
    <a href="<?= url('admin.facets.type', ['type' => 'category']); ?>">
      <?= __('web.facets'); ?>
    </a>
  </li>
<?php endif; ?>