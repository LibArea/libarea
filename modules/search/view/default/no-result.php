<?php if ($query) : ?>
  <p><?= __('no.search.results'); ?></p>
<?php else : ?>
  <p><?= __('search.home'); ?>...</p>
<?php endif; ?>

<a class="mb20 block" href="/">
  <i class="bi-house"></i>
  <?= __('go.to'); ?>
  <span class="lowercase"><?= __('to.main'); ?></span>...
</a>