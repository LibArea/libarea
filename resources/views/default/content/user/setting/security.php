<main>
  <?= insert('/content/user/setting/nav', ['data' => $data]); ?>

  <form class="max-w300" action="<?= url('setting.change', ['type' => 'security']); ?>" method="post">
    <?php csrf_field(); ?>
    <?= component('setting-security'); ?>
  </form>
</main>

<aside>
  <div class="box bg-beige">
    <?= __('help.security_info'); ?>
  </div>
</aside>