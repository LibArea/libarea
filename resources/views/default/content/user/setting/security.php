<main>
  <?= insert('/content/user/setting/nav'); ?>
  <div class="indent-body">
    <form class="max-w300" action="<?= url('setting.edit.security', method: 'post'); ?>" method="post">
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