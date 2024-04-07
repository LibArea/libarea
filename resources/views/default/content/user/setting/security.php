<main>
  <div class="indent-body">
    <?= insert('/content/user/setting/nav'); ?>

    <form class="max-w300" action="<?= url('setting.edit.security', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/setting-security'); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box bg-beige">
    <?= __('help.security_info'); ?>
  </div>
</aside>