<main class="w-100">
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <form class="mb20" action="<?= url('setting.edit.notification', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>
      <?= insert('/content/user/setting/form/setting-notifications', ['data' => $data]); ?>
    </form>
    <div class="box-info">
      <?= __('help.notification_info'); ?>
    </div>
  </div>
</main>