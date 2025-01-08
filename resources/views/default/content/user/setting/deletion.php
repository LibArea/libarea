<main>
  <h1 class="title"><?= __('app.delete_profile'); ?></h1>
  <div class="max-w-md mb20">
    <p><?= __('app.read_carefully'); ?></p>
    <?= __('app.read_information'); ?>
  </div>
  <div class="flex flex-row items-center justify-between">
    <a class="gray-600" href="<?php url('setting'); ?>">
      < <?= __('app.back'); ?></a>
        <a href="<?= url('delete.activation'); ?>" class="red"><?= __('app.delete_profile'); ?></a>
  </div>
</main>

<aside>
  <div class="box">
    <?= __('app.read_carefully'); ?>
  </div>
</aside>