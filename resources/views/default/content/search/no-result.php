<?php if ($query) : ?>
  <p><?= __('search.no_results'); ?></p>
<?php else : ?>
  <p><?= __('search.home'); ?>...</p>
<?php endif; ?>

<a class="mb20" href="/">
  <i class="bi-house"></i>
  <?= __('search.to_website'); ?>
</a>...