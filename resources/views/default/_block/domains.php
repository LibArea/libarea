<?php if (!empty($data)) { ?>
  <h3 class="uppercase-box"><?= Translate::get('domains'); ?></h3>
  <?php foreach ($data as  $domain) { ?>
    <a class="text-sm gray" href="<?= getUrlByName('domain', ['domain' => $domain['item_domain']]); ?>">
      <i class="bi-link-45deg middle"></i> <?= $domain['item_domain']; ?>
      <sup class="text-sm"><?= $domain['item_count']; ?></sup>
    </a><br>
  <?php } ?>
<?php } else { ?>
  <p><?= Translate::get('no.content'); ?>...</p>
<?php } ?>