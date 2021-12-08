<?php if (!empty($data)) { ?>
  <div class="uppercase mb5 mt5 size-14"><?= Translate::get('domains'); ?></div>
  <?php foreach ($data as  $domain) { ?>
    <a class="size-14 gray" href="<?= getUrlByName('domain', ['domain' => $domain['item_url_domain']]); ?>">
      <i class="bi bi-link-45deg middle"></i> <?= $domain['item_url_domain']; ?>
      <sup class="size-13"><?= $domain['item_count']; ?></sup>
    </a><br>
  <?php } ?>
<?php } else { ?>
  <p><?= Translate::get('there are no domains'); ?>...</p>
<?php } ?>