<?php if ($query) { ?>
  <p><?= Translate::get('no.search.results'); ?></p>
<?php } else { ?>
  <p><?= Translate::get('search.home'); ?>...</p>
<?php } ?>

<a class="mb20 block" href="/">
  <i class="bi-house"></i>
  <?= Translate::get('go to'); ?>
  <span class="lowercase"><?= Translate::get('to.main'); ?></span>...
</a>