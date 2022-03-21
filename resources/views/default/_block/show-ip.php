<?php if ($user['trust_level'] == UserData::REGISTERED_ADMIN) { ?>
  <a class="gray-600 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $ip]); ?>">
    <?= $ip; ?>
  </a> 
  <?php if ($publ == 0) { ?>
     <span class="ml15 red lowercase"><?= Translate::get('audits'); ?></span>
  <?php } ?>
<?php } ?>