<?= insert('/_block/add-js-css'); ?>

<main>
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <?= insert('/_block/form/cropper/user-avatar', ['data' => $data['user']]); ?>
    <br>
    <?= insert('/_block/form/cropper/user-cover', ['data' => $data['user']]); ?>
  </div>
</main>
<aside>
  <div class="box">
    <?= __('help.avatar_info'); ?>
  </div>
</aside>