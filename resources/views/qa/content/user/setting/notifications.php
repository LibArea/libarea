<main class="col-two">
  <?= insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form id="notif" method="post">
      <?php csrf_field(); ?>
      <?= component('setting-notifications', ['data' => $data]); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box bg-violet text-sm">
    <?= __('help.notification_info'); ?>
  </div>
</aside>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('setting.notif.edit'),
    'redirect'  => url('setting', ['type' => 'notifications']),
    'success'   => __('msg.password_changed'),
    'id'        => 'form#notif'
  ]
); ?>