<main>
  <?= insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form class="max-w300" action="<?= url('setting.change', ['type' => 'security']); ?>" method="post">
      <?php csrf_field(); ?>
      <?= component('setting-security'); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box text-sm">
    <?= __('help.security_info'); ?>
  </div>
</aside>