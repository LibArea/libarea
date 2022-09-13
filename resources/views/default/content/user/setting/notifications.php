<main>
  <?= insert('/content/user/setting/nav'); ?>
  <form class="max-w780" action="<?= url('setting.change', ['type' => 'notification']); ?>" method="post">
    <?php csrf_field(); ?>
    <?= insert('/_block/form/setting-notifications', ['data' => $data]); ?>
  </form>
</main>

<aside>
  <div class="box bg-beige">
    <?= __('help.notification_info'); ?>
  </div>
</aside>