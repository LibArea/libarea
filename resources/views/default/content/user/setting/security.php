<main>
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <form class="max-w-sm" action="<?= url('setting.edit.security', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/setting-security'); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box">
    <?= __('help.security_info'); ?>
  </div>
</aside>