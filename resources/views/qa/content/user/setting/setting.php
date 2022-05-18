<main class="col-two">
  <?= insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form class="max-w780" id="setting" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>
      <?= component('setting-security', ['data' => $data]); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box text-sm bg-violet">
    <?= __('help.setting_info'); ?>
  </div>
</aside>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('setting.edit'),
    'redirect'  => url('setting', ['type' => 'security']),
    'success'   => __('msg.password_changed'),
    'id'        => 'form#setting'
  ]
); ?>