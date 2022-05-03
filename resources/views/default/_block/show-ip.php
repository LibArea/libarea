<?php if (UserData::checkAdmin()) : ?>
  <a class="gray-600 ml10" href="<?= url('admin.logip', ['ip' => $ip]); ?>">
    <?= $ip; ?>
  </a> 
  <?php if ($publ == 0) : ?>
     <span class="ml15 red lowercase"><?= __('app.audits'); ?></span>
  <?php endif; ?>
<?php endif; ?>