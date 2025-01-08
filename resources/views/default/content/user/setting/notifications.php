<main>
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <form class="max-w-md" action="<?= url('setting.edit.notification', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/setting-notifications', ['data' => $data]); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box">
    <?= __('help.notification_info'); ?>
  </div>
</aside>