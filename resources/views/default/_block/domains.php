<?php if (!empty($data)) : ?>
  <h3 class="uppercase-box"><?= __('app.domains'); ?></h3>
  <?php foreach ($data as  $domain) : ?>
    <a class="text-sm gray" href="<?= url('domain', ['domain' => $domain['item_domain']]); ?>">
      <i class="bi-link-45deg middle"></i> <?= $domain['item_domain']; ?>
      <sup class="text-sm"><?= $domain['item_count']; ?></sup>
    </a><br>
  <?php endforeach; ?>
<?php else : ?>
  <p><?= __('app.no_content'); ?>...</p>
<?php endif; ?>