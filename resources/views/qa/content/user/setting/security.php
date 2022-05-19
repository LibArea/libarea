<main class="col-two">
  <?= insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form class="max-w300" id="security" method="post">
      <?php csrf_field(); ?>
      <?= component('setting-security'); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box bg-violet text-sm">
    <?= __('help.security_info'); ?>
  </div>
</aside>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('setting.change', ['type' => 'security']),
    'redirect'  => url('setting', ['type' => 'security']),
    'success'   => __('msg.password_changed'),
    'id'        => 'form#security'
  ]
); ?>