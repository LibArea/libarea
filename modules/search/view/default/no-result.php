<?php if ($query) { ?>
  <p><?= Translate::get('no.search.results'); ?></p>
<?php } else { ?>
  <p><?= Translate::get('search.home'); ?>...</p>
<?php } ?>

<a class="mb20 block" href="/">
  <img class="right mr30 w40" src="<?= Config::get('meta.img_footer_path'); ?>">
  <i class="bi bi-house"></i>
  <?= Translate::get('go to'); ?>
  <span class="lowercase"><?= Translate::get('to main'); ?></span>...
</a>