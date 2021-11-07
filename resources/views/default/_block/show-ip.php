<?php if ($user_trust_level == 5) { ?>
  <a class="gray-light ml10" href="<?= getUrlByName('admin.logip', ['ip' => $ip]); ?>">
    <?= $ip; ?>
  </a>
<?php } ?>