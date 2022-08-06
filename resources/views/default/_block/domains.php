<?php if (!empty($data)) : ?>
  <h4 class="uppercase-box"><?= __('app.domains'); ?></h4>
  <?php foreach ($data as  $domain) : ?>
    <a class="text-sm gray" href="<?= url('domain', ['domain' => $domain['item_domain']]); ?>">
      <svg class="icons">
        <use xlink:href="/assets/svg/icons.svg#link"></use>
      </svg> <?= $domain['item_domain']; ?>
      <sup class="text-sm"><?= $domain['item_count']; ?></sup>
    </a><br>
  <?php endforeach; ?>
<?php else : ?>
  <p><?= __('app.no_content'); ?>...</p>
<?php endif; ?>