<?php $user_count_site = $data['user_count_site'] ?? false; ?>

<li>
  <a <?= is_current(url('web.user.sites')) ? 'class="active"' : ''; ?> href="<?= url('web.user.sites'); ?>">
    <?= __('web.my_website'); ?>
    <?php if ($user_count_site != false) : ?>
      (<?= $user_count_site; ?>)
    <?php endif; ?>
  </a>
</li>

<li>
  <a <?= is_current(url('web.bookmarks')) ? 'class="active"' : ''; ?> href="<?= url('web.bookmarks'); ?>">
    <?= __('web.bookmarks'); ?>
  </a>
</li>

<?php if ($container->user()->admin()) : ?>
  <li class="uppercase-box mt15"><?= __('admin.home'); ?></li>

  <li>
    <a <?= is_current(url('web.audits')) ? 'class="active"' : ''; ?> href="<?= url('web.audits'); ?>">
      <?= __('web.audits'); ?>
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
    <a href="<?= url('facet.form.add', ['type' => 'category']); ?>">
      <?= __('web.add_category'); ?>
    </a>
  </li>

  <li>
    <a href="<?= url('admin.facets.type', ['type' => 'category']); ?>">
      <?= __('web.facets'); ?>
    </a>
  </li>
<?php endif; ?>