<main>
  <?= insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form action="<?= url('setting.change', ['type' => 'notification']); ?>" method="post">
      <?php csrf_field(); ?>
      <?= component('setting-notifications', ['data' => $data]); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box bg-beige">
    <?= __('help.notification_info'); ?>
  </div>
</aside>