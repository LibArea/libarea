<main class="max">
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <form class="mb20" action="<?= url('setting.edit.security', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>
      <?= insert('/content/user/setting/form/setting-security'); ?>
    </form>
    <div class="box-info">
      <?= __('help.security_info'); ?>
    </div>
  </div>
</main>