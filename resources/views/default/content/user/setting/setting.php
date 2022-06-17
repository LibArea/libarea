<main>
  <?= insert('/content/user/setting/nav'); ?>

  <form class="max-w780" action="<?= url('setting.change', ['type' => 'setting']); ?>" method="post">
    <?php csrf_field(); ?>
    <?= component('setting', ['data' => $data]); ?>
  </form>
</main>

<aside>
  <div class="box bg-beige">
    <?= __('help.setting_info'); ?>
  </div>
</aside>