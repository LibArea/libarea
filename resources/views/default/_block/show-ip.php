<?php if ($uid['user_trust_level'] == UserData::REGISTERED_ADMIN) { ?>
  <a class="gray-400 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $ip]); ?>">
    <?= $ip; ?>
  </a>
<?php } ?>