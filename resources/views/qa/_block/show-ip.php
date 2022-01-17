<?php if ($user['trust_level'] == UserData::REGISTERED_ADMIN) { ?>
  <a class="gray-400 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $ip]); ?>">
    <?= $ip; ?>
  </a> 
  <?php if ($publ == 0) { ?>
     <span class="ml15 red-500 lowercase"><?= Translate::get('audits'); ?></span>
  <?php } ?>
<?php } ?>