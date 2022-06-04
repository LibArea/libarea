<main>
  <?= insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form class="max-w780" action="<?= url('setting.change', ['type' => 'setting']); ?>" method="post">
      <?php csrf_field(); ?>
      <?= component('setting', ['data' => $data]); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box text-sm">
    <?= __('help.setting_info'); ?>
  </div>
</aside>