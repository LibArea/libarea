<main class="col-two">
  <div class="box pt0">
    <h2><?= __('app.add_post'); ?></h2>

    <form class="max-w780" action="<?= url('content.create', ['type' => 'post']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?= component('add-post', ['data' => $data]); ?>
    </form>

  </div>
</main>
<aside>
   <div class="box text-sm bg-violet">
    <h3 class="uppercase-box"><?= __('app.help'); ?></h3>
    <?= __('help.add_post'); ?>
  </div>
</aside>

 