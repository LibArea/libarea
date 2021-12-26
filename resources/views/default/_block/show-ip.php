<?php if ($user_trust_level == 5) { ?>
  <a class="gray-400 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $ip]); ?>">
    <?= $ip; ?>
  </a>
<?php } ?>