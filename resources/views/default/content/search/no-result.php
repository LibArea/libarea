<?php if ($query) : ?>
  <p><?= __('search.no_results'); ?></p>
<?php else : ?>
  <p><?= __('search.home'); ?>...</p>
<?php endif; ?>

<a class="mb20" href="/">
  <svg class="icons">
    <use xlink:href="/assets/svg/icons.svg#home"></use>
  </svg> <?= __('search.to_website'); ?>
</a>...