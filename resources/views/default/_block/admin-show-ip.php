<?php if (UserData::checkAdmin()) : ?>
  <div class="gray-600 relative">
    <span class="trigger gray-600 text-sm">ip</span>
    <ul class="dropdown">
      <li>
        <a class="gray-600" href="<?= url('admin.logip', ['ip' => $ip]); ?>">&#183; <?= $ip; ?></a>
      <li>
    </ul>
  </div>
  <?php if ($publ == 0) : ?>
    <span class="ml15 red lowercase"><?= __('app.audits'); ?></span>
  <?php endif; ?>
<?php endif; ?>